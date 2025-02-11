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
          // Nourriture
          node["amenity"="fast_food"](area.searchArea);
          node["shop"="bakery"](area.searchArea);
          node["shop"="supermarket"](area.searchArea);
          node["shop"="mall"](area.searchArea);

          // Santé
          node["amenity"="pharmacy"](area.searchArea);
          node["amenity"="hospital"](area.searchArea);

          // Transport
          node["amenity"="fuel"](area.searchArea);
          node["amenity"="parking"](area.searchArea);
          node["amenity"="bicycle_rental"](area.searchArea);

          // Loisirs
          node["amenity"="cinema"](area.searchArea);
          node["amenity"="theatre"](area.searchArea);
          node["amenity"="nightclub"](area.searchArea);
          node["amenity"="library"](area.searchArea);
          node["amenity"="cafe"](area.searchArea);
          node["tourism"="museum"](area.searchArea);
          node["leisure"="park"](area.searchArea);

          // Services
          node["amenity"="bank"](area.searchArea);
          node["amenity"="atm"](area.searchArea);
          node["amenity"="post_office"](area.searchArea);
          node["amenity"="school"](area.searchArea);

          // Hébergement
          node["tourism"="hotel"](area.searchArea);

          // Other
          node["amenity"="toilets"](area.searchArea);
          node["amenity"="drinking_water"](area.searchArea);
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

            $type = $this->getTypeFromTags($element['tags']);
            $name = $element['tags']['name'] ?? $this->getDefaultName($type);
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

    private function getDefaultName(string $type): string
    {
        return match ($type) {
            'toilets' => 'Toilettes publiques',
            'drinking_water' => "Point d'eau potable",
            default => 'Lieu sans nom',
        };
    }
}
