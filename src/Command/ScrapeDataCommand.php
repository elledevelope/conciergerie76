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
        $url = 'https://www.visiterouen.com/patrimoines/nature/parenthese-jardin/parcs-jardins/'; 
        $response = $this->client->request('GET', $url);
        $html = $response->getContent();

        // Use DOMDocument to parse the HTML
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        // Query for places (e.g., listings with class 'listing_title' might contain the name)
        $places = $xpath->query('//div[contains(@class, "item-infos-title")]');

        foreach ($places as $place) {
            $name = trim($place->textContent);
        
            // Get the address, but ensure it doesn't end up as null
            $addressNode = $xpath->query('.//span[contains(@class, "location")]//text()', $place);
            $address = $addressNode->length > 0 ? trim($addressNode->item(0)->textContent) : '';
        
            // Get the phone number (allow null if not found)
            $phoneNode = $xpath->query('.//span[contains(@class, "phone")]//text()', $place);
            $phone = $phoneNode->length > 0 ? trim($phoneNode->item(0)->textContent) : null;
        
            // Get the URL (allow null if not found)
            $urlNode = $xpath->query('.//a[contains(@class, "listing_link")]/@href', $place);
            $url = $urlNode->length > 0 ? trim($urlNode->item(0)->nodeValue) : null;
        
            // Debugging output for the scraped data
            echo "Scraped Data: $name, $address, $phone, $url\n";
        
            // Create and persist the Place entity
            $placeEntity = new Place();
            $placeEntity->setName($name);
            $placeEntity->setAddress($address);  // Pass the empty string if address is not found
            $placeEntity->setPhone($phone);
            $placeEntity->setUrl($url);
        
            // Persist the entity
            $this->entityManager->persist($placeEntity);
        
            // Optional: Sleep to avoid rate-limiting
            echo "Sleeping for 2 seconds...\n";
            sleep(2);
        }
        
        
        // Save all changes to the database
        $this->entityManager->flush();
        
        $output->writeln('Data scraped and saved.');

        return Command::SUCCESS;
    }
}
