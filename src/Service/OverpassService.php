<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Service;

class OverpassService
{
    private HttpClientInterface $httpClient;
    private EntityManagerInterface $entityManager;

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
    }

    public function fetchAndStoreData(): void
    {
        $overpassQuery = <<<'EOT'
        [out:json];
        area[name="Rouen"]->.searchArea;
        (
          node["amenity"="fast_food"](area.searchArea);
          node["shop"="bakery"](area.searchArea);
          node["shop"="supermarket"](area.searchArea);
          node["shop"="convenience"](area.searchArea);
          node["shop"="mall"](area.searchArea);
          node["shop"](area.searchArea);
        
          node["amenity"="pharmacy"](area.searchArea);
          node["amenity"="hospital"](area.searchArea);
        
          node["amenity"="fuel"](area.searchArea);
          node["amenity"="parking"](area.searchArea);
          node["amenity"="bicycle_rental"](area.searchArea);
        
          node["amenity"="cinema"](area.searchArea);
          node["amenity"="theatre"](area.searchArea);
          node["amenity"="nightclub"](area.searchArea);
          node["amenity"="library"](area.searchArea);
        
          node["amenity"="bank"](area.searchArea);
          node["amenity"="atm"](area.searchArea);
          node["amenity"="post_office"](area.searchArea);
          node["amenity"="school"](area.searchArea);
          node["amenity"="university"](area.searchArea);
          node["amenity"="hairdresser"](area.searchArea);
          node["amenity"="dry_cleaning"](area.searchArea);
          node["amenity"="car_repair"](area.searchArea);
        
          node["tourism"="hotel"](area.searchArea);
        
          node["amenity"="toilets"](area.searchArea);
        );
        out body;
        EOT;

        $url = "https://overpass-api.de/api/interpreter?data=" . urlencode($overpassQuery);
        $response = $this->httpClient->request('GET', $url);
        $data = $response->toArray();

        if (!isset($data['elements'])) {
            return; // No data found
        }

        foreach ($data['elements'] as $element) {
            if (!isset($element['lat'], $element['lon'])) {
                continue; // Skip elements without coordinates
            }

            $name = $element['tags']['name'] ?? 'Unknown';
            $type = $this->getTypeFromTags($element['tags']);
            $latitude = $element['lat'];
            $longitude = $element['lon'];
            $phone = $element['tags']['phone'] ?? null;
            $website = $element['tags']['website'] ?? null;
            $address = $element['tags']['addr:street'] ?? null;

            $service = new Service();
            $service->setName($name);
            $service->setType($type);
            $service->setLatitude($latitude);
            $service->setLongitude($longitude);
            $service->setPhone($phone);
            $service->setWebsite($website);
            $service->setAddress($address);

            $this->entityManager->persist($service);
        }

        $this->entityManager->flush();
    }

    private function getTypeFromTags(array $tags): string
    {
        return $tags['amenity'] ?? $tags['tourism'] ?? $tags['leisure'] ?? 'unknown';
    }
}
