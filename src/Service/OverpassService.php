<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Service;

class OverpassService
{
    private HttpClientInterface $httpClient;
    private EntityManagerInterface $entityManager;

    // Constructor to inject HttpClient and EntityManager dependencies
    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
    }

    // Main method to fetch data from the Overpass API and store it in the database
    public function fetchAndStoreData(): void
    {
        // Overpass QL query to retrieve points of interest (POIs) in the "Rouen" area
        $overpassQuery = <<<'EOT'
        [out:json];
        area[name="Rouen"]->.searchArea;
        (
          // Categories of interest to fetch
          node["amenity"="fast_food"](area.searchArea);
          node["shop"="bakery"](area.searchArea);
          node["shop"="supermarket"](area.searchArea);
          node["shop"="mall"](area.searchArea);

          // Health-related amenities
          node["amenity"="pharmacy"](area.searchArea);
          node["amenity"="hospital"](area.searchArea);

          // Transportation amenities
          node["amenity"="fuel"](area.searchArea);
          node["amenity"="parking"](area.searchArea);
          node["amenity"="bicycle_rental"](area.searchArea);

          // Leisure and cultural amenities
          node["amenity"="cinema"](area.searchArea);
          node["amenity"="theatre"](area.searchArea);
          node["amenity"="nightclub"](area.searchArea);
          node["amenity"="library"](area.searchArea);
          node["amenity"="cafe"](area.searchArea);
          node["tourism"="museum"](area.searchArea);
          node["leisure"="park"](area.searchArea);

          // Service-related amenities
          node["amenity"="bank"](area.searchArea);
          node["amenity"="atm"](area.searchArea);
          node["amenity"="post_office"](area.searchArea);
          node["amenity"="school"](area.searchArea);

          // Accommodation
          node["tourism"="hotel"](area.searchArea);

          // Miscellaneous amenities
          node["amenity"="toilets"](area.searchArea);
          node["amenity"="drinking_water"](area.searchArea);
        );
        out body;
        EOT;

        // Construct the Overpass API URL with the encoded query
        $url = "https://overpass-api.de/api/interpreter?data=" . urlencode($overpassQuery);
        
        // Send an HTTP GET request to fetch data
        $response = $this->httpClient->request('GET', $url);
        
        // Convert the response to an associative array
        $data = $response->toArray();

        // Check if the 'elements' key exists in the response; return if no data is found
        if (!isset($data['elements'])) {
            return; // No data found
        }

        // Loop through each element in the response data
        foreach ($data['elements'] as $element) {
            // Skip elements that don't have latitude or longitude
            if (!isset($element['lat'], $element['lon'])) {
                continue;
            }

            // Determine the type of service based on tags
            $type = $this->getTypeFromTags($element['tags']);

            // Extract the name or assign a default name if not available
            $name = $element['tags']['name'] ?? $this->getDefaultName($type);

            // Extract coordinates
            $latitude = $element['lat'];
            $longitude = $element['lon'];

            // Optional fields for phone, website, and address
            $phone = $element['tags']['phone'] ?? null;
            $website = $element['tags']['website'] ?? null;
            $address = $element['tags']['addr:street'] ?? null;

            // Create a new Service entity and populate its properties
            $service = new Service();
            $service->setName($name);
            $service->setType($type);
            $service->setLatitude($latitude);
            $service->setLongitude($longitude);
            $service->setPhone($phone);
            $service->setWebsite($website);
            $service->setAddress($address);

            // Persist the service entity to the database
            $this->entityManager->persist($service);
        }

        // Flush the persisted entities to the database
        $this->entityManager->flush();
    }

    // Determine the type of service based on tags using a series of match statements
    private function getTypeFromTags(array $tags): string
    {
        return match (true) {
            isset($tags['amenity']) => match ($tags['amenity']) {
                'fast_food' => 'fast_food',
                'pharmacy' => 'pharmacy',
                'hospital' => 'hospital',
                'fuel' => 'fuel',
                'parking' => 'parking',
                'bicycle_rental' => 'bicycle_rental',
                'cinema' => 'cinema',
                'theatre' => 'theatre',
                'nightclub' => 'nightclub',
                'library' => 'library',
                'cafe' => 'cafe',
                'bank' => 'bank',
                'atm' => 'atm',
                'post_office' => 'post_office',
                'school' => 'school',
                'toilets' => 'toilets',
                'drinking_water' => 'drinking_water',
                default => 'other_service',
            },
            isset($tags['shop']) => match ($tags['shop']) {
                'bakery' => 'boulangerie',
                'supermarket' => 'supermarche',
                'mall' => 'centre_commercial',
                default => 'shop',
            },
            isset($tags['tourism']) => match ($tags['tourism']) {
                'museum' => 'museum',
                'hotel' => 'hotel',
                default => 'tourism',
            },
            isset($tags['leisure']) => match ($tags['leisure']) {
                'park' => 'park',
                default => 'leisure',
            },
            default => 'unknown',
        };
    }

    // Get a default name based on the type of service
    private function getDefaultName(string $type): string
    {
        return match ($type) {
            'toilets' => 'Toilettes publiques',
            'drinking_water' => "Point d'eau potable",
            default => 'Lieu sans nom',
        };
    }
}
