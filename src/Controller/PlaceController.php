<?php

namespace App\Controller;

use App\Entity\Place;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    /**
     * @Route("/scrape", name="scrape_places")
     */
    #[Route('/scrape', name: 'scrape_places')]
    public function scrape(EntityManagerInterface $entityManager): Response
    {
        // Overpass API query for restaurants, parks, and service stations in a region (latitude/longitude bounds)
        $overpassQuery = "
        [out:json];
        (
          node['amenity'='restaurant'](49.4,1.0,49.5,1.1);
          node['amenity'='park'](49.4,1.0,49.5,1.1);
          node['amenity'='fuel'](49.4,1.0,49.5,1.1);
        );
        out body;
        ";

        $url = "https://overpass-api.de/api/interpreter?data=" . urlencode($overpassQuery);
        
        // Fetch data from Overpass API
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        
        // Iterate through the response and save the places in the database
        foreach ($data['elements'] as $element) {
            $place = new Place();
            $place->setName($element['tags']['name'] ?? 'Unnamed Place');
            $place->setLatitude($element['lat']);
            $place->setLongitude($element['lon']);
            $place->setType($element['tags']['amenity']);

            $entityManager->persist($place);
        }

        $entityManager->flush();

        return new Response('Places scraped and stored successfully!');
    }
}
