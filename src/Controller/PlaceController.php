<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlaceController extends AbstractController
{
    // pass $services 
    public function index(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();

        return $this->render('place/index.html.twig', [
            'services' => $services,
        ]);
    }


    // Route to display parks
    // #[Route('/parcs', name: 'app_parcs')]
    // public function showParks(PlaceRepository $placeRepository): Response
    // {
    //     // Fetch all parks from the database (You may want to filter places by category)
    //     $parks = $placeRepository->findBy(['type' => 'parc']);

    //     return $this->render('place/index.html.twig', [
    //         'places' => $parks,
    //         'type' => 'Parcs',
    //     ]);
    // }

    #[Route('/place/{id}', name: 'place_show')]
    public function show(int $id, ServiceRepository $serviceRepository): Response
    {
        $service = $serviceRepository->find($id);

        // dump($service);

        if (!$service) {
            throw $this->createNotFoundException('The place does not exist');
        }

        return $this->render('place/show.html.twig', [
            'service' => $service,
        ]);
    }
}
