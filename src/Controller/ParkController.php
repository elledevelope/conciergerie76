<?php

// src/Controller/ParkController.php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ParkController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/parks', name: 'app_parks')]
    public function index(PlaceRepository $placeRepository)
    {
        // Fetch all places
        $places = $placeRepository->findAll();

        // Prepare coordinates array for the places
        $placesWithCoordinates = [];

        foreach ($places as $place) {
            // Prepare place name
            $placeName = $place->getName();

            // Use Nominatim Geocoding API to get coordinates based on place name
            $response = $this->client->request('GET', 'https://nominatim.openstreetmap.org/search', [
                'query' => [
                    'q' => $placeName,
                    'format' => 'json',
                    'addressdetails' => 1,
                    'limit' => 1, // Limit to the best matching result
                ]
            ]);

            // Parse response and check if coordinates are available
            $data = $response->toArray();
            if (count($data) > 0) {
                $latitude = $data[0]['lat'];
                $longitude = $data[0]['lon'];
            } else {
                // If no coordinates are found, use default coordinates (e.g., center of Paris)
                $latitude = 48.8566;
                $longitude = 2.3522;
            }

            // Add place with coordinates to the array
            $placesWithCoordinates[] = [
                'name' => $place->getName(),
                'latitude' => $latitude,
                'longitude' => $longitude
            ];
        }

        // Pass data to the Twig template
        return $this->render('park/index.html.twig', [
            'places' => $placesWithCoordinates,
        ]);
    }
}
