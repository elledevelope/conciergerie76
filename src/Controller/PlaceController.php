<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlaceController extends AbstractController
{
    // In your controller, you should pass the services to the template like this
    public function index(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();  // or any other query to get services

        return $this->render('place/index.html.twig', [
            'services' => $services, // Pass the services to the template
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

    #[Route('/place/{id}', name: 'place_show')]
    public function show(int $id, ServiceRepository $serviceRepository): Response
    {
        // Fetch the service by its ID
        $service = $serviceRepository->find($id);

        // Debug: Output the service object to check if it has the expected data
        dump($service);  // This will output the service entity in the browser

        // If service is not found, throw a 404 error
        if (!$service) {
            throw $this->createNotFoundException('The place does not exist');
        }

        // Render the template and pass the service data to it
        return $this->render('place/show.html.twig', [
            'service' => $service,
        ]);
    }
}
