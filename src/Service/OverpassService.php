<?php
// src/Service/OverpassService.php
namespace App\Service;

use App\Entity\DrinkingWaterNode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OverpassService
{
    private $httpClient;
    private $entityManager;

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
    }

    public function fetchDrinkingWaterNodes(float $minLat, float $minLon, float $maxLat, float $maxLon)
    {
        // Define the Overpass API URL
        $overpassUrl = 'http://overpass-api.de/api/interpreter';

        // Overpass query with bounding box, formatted for POST
        $query = sprintf(
            '[out:json];node[amenity=drinking_water](%f,%f,%f,%f);out;',
            $minLat,
            $minLon,
            $maxLat,
            $maxLon
        );

        try {
            // Send the query using the POST method (sending the query in the body)
            $response = $this->httpClient->request('POST', $overpassUrl, [
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'body' => ['data' => $query]
            ]);

            // Convert the JSON response to an array
            $data = $response->toArray();

            // Get the nodes from the response
            $nodes = $data['elements'] ?? [];

            // Iterate over the nodes and store them in the database
            foreach ($nodes as $nodeData) {
                $node = new DrinkingWaterNode();
                $node->setLat($nodeData['lat']);
                $node->setLon($nodeData['lon']);
                $node->setName($nodeData['tags']['name'] ?? null);

                // Persist the node in the database
                $this->entityManager->persist($node);
            }

            // Commit the changes to the database
            $this->entityManager->flush();

        } catch (TransportExceptionInterface $e) {
            // Handle the exception if the request fails
            // You can log it or return an error response
            throw new \RuntimeException('Error during Overpass API request: ' . $e->getMessage());
        }
    }
}
