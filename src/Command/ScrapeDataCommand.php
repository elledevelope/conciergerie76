<?php

namespace App\Command;

use App\Entity\Place;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'app:scrape-data')]
class ScrapeDataCommand extends Command
{
    private HttpClientInterface $client;
    private EntityManagerInterface $entityManager;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Step 1: Scrape a list of places (e.g., a page with multiple links to individual places)
        $url = 'https://rouen.fr/parc';
        $response = $this->client->request('GET', $url);
        $html = $response->getContent();

        // Use DOMDocument to parse the HTML
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        // Scrape a list of places (page with multiple links to individual places)
        $placeLinks = $xpath->query('//a[@href="/parc"]/following-sibling::ul[@class="menu"]//a');  // Select <a> tags inside <ul class="menu"> that follow <a> with href='/parc'



        foreach ($placeLinks as $link) {
            // Cast the $link to a DOMElement to be able to use getAttribute()
            if ($link instanceof \DOMElement) {
                $placeUrl = $link->getAttribute('href');  // Now we can safely call getAttribute()

                // Base URL of the list page
                $baseUrl = 'https://rouen.fr/parc';

                // Construct the full place URL
                // First, check if the $placeUrl is already an absolute URL (i.e., starts with "http").
                if (strpos($placeUrl, 'http') === 0) {
                    // If it's an absolute URL, use it as is
                    $fullPlaceUrl = $placeUrl;
                } else {
                    // If it's a relative URL (like '/saintecatherine'), we need to adjust the base URL
                    // Strip '/parc' from the base URL and append the relative path
                    // Example: If $placeUrl is '/saintecatherine', this will become 'https://rouen.fr/saintecatherine'
                    $fullPlaceUrl = rtrim('https://rouen.fr', '/') . $placeUrl; // Concatenate the root URL with the relative URL
                }


                // Now, you can proceed with scraping the individual place page
                $placeResponse = $this->client->request('GET', $fullPlaceUrl);
                $placeHtml = $placeResponse->getContent();

                // Parse the individual place page HTML
                $placeDom = new \DOMDocument();
                @$placeDom->loadHTML($placeHtml);   // Load the HTML of the individual place page
                $placeXpath = new \DOMXPath($placeDom);

                // Scrape data from the individual place page:
                // ------------ NAME
                // Select the <h1> tag containing the name
                $nameNode = $placeXpath->query('//h1');
                // Extract the name text if <h1> is found
                if ($nameNode->length > 0) {
                    $name = trim($nameNode->item(0)->textContent);  // Extract the text from the <h1> tag
                } else {
                    $name = "Unknown Place";  // Fallback in case <h1> is not found
                }

                // ---------- ADDRESS
                // Extract street address from 'street-block'
                $streetNode = $placeXpath->query('//div[contains(@class, "street-block")]');
                $street = $streetNode->length > 0 ? trim($streetNode->item(0)->textContent) : null;

                // Extract postcode and country from 'locality-block' 
                $postcodeNode = $placeXpath->query('//div[contains(@class, "locality-block")]');
                $postcode = $postcodeNode->length > 0 ? trim($postcodeNode->item(0)->textContent) : null;

                // Combine all parts to form the full address
                $address = trim($street . ' ' . $postcode);

                // full address :
                // echo "Full Address: $address\n";


                // ---------- TEL
                $phoneNode = $placeXpath->query('//div[contains(@class, "field-content")]//p');
                $phone = $phoneNode->length > 0 ? trim($phoneNode->item(0)->textContent) : null;



                // ---------- URL
                // $urlNode = $placeXpath->query('//a[contains(@class, "place-url")]/@href');
                // $url = $urlNode->length > 0 ? trim($urlNode->item(0)->nodeValue) : null;

                // Save the fullPlaceUrl (constructed above) to the database
                $url = $fullPlaceUrl; // Use the full URL we constructed



                // Debugging output for the scraped data
                echo "Scraped Data: $name, $address, $phone, $url\n";

                // Create and persist the Place entity
                $placeEntity = new Place();
                $placeEntity->setName($name);
                $placeEntity->setAddress($address);
                $placeEntity->setPhone($phone);
                $placeEntity->setUrl($url);

                // Persist the entity
                $this->entityManager->persist($placeEntity);

                // Optional: Sleep to avoid rate-limiting
                sleep(2);
            }
        }

        // Step 3: Save all changes to the database
        try {
            $this->entityManager->flush();  // Commit the changes to the DB
            $output->writeln('Data scraped and saved successfully.');
        } catch (\Exception $e) {
            $output->writeln('Error saving data: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
