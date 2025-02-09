<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlaceController extends AbstractController
{
    #[Route('/place', name: 'app_place')]
    public function index(): Response
    {
        return $this->render('place/index.html.twig', [
            'controller_name' => 'PlaceController',
        ]);
    }

    // Route to display parks
    #[Route('/parcs', name: 'app_parcs')]
    public function showParks(PlaceRepository $placeRepository): Response
    {
        // Fetch all parks from the database (You may want to filter places by category)
        $parks = $placeRepository->findBy(['type' => 'parc']); 

        return $this->render('place/index.html.twig', [
            'places' => $parks,
            'type' => 'Parcs',
        ]);
    }
}
