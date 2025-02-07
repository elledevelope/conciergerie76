<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MapController extends AbstractController
{
    #[Route('/map', name: 'app_map')]
    public function index(PlaceRepository $placeRepository): Response
    {
        $places = $placeRepository->findAll();
        // dump($places); die;

    // Convert Doctrine entities into plain arrays
    $placesArray = array_map(function ($place) {
        return [
            'id' => $place->getId(),
            'name' => $place->getName(),
            'latitude' => $place->getLatitude(),
            'longitude' => $place->getLongitude(),
            'type' => $place->getType(),
        ];
    }, $places);

    return $this->render('map/index.html.twig', [
        'places' => $placesArray, // Pass array, NOT entity objects
    ]);
    }
}
