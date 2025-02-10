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

    public function fetchAndStoreData()
    {
        $overpassQuery = <<<'EOT'
        [out:json];
        area[name="Rouen"]->.searchArea;
        (
          node["amenity"="cafe"](area.searchArea);
          node["tourism"="museum"](area.searchArea);
          node["leisure"="park"](area.searchArea);
        );
        out body;
        EOT;

        $url = "https://overpass-api.de/api/interpreter?data=" . urlencode($overpassQuery);
        $response = $this->httpClient->request('GET', $url);
        $data = $response->toArray();

        foreach ($data['elements'] as $element) {
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
        if (isset($tags['amenity'])) {
            return $tags['amenity'];
        }
        if (isset($tags['tourism'])) {
            return $tags['tourism'];
        }
        if (isset($tags['leisure'])) {
            return $tags['leisure'];
        }
        return 'unknown';
    }
}
