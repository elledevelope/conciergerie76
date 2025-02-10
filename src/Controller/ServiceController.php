<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    // #[Route('/api/services', name: 'api_services', methods: ['GET'])]
    // public function getServices(ServiceRepository $serviceRepository): JsonResponse
    // {
    //     $services = $serviceRepository->findAll();

    //     $data = array_map(function ($service) {
    //         return [
    //             'id' => $service->getId(),
    //             'name' => $service->getName(),
    //             'type' => $service->getType(),
    //             'address' => $service->getAddress(),
    //             'phone' => $service->getPhone(),
    //             'website' => $service->getWebsite(),
    //             'latitude' => $service->getLatitude(),
    //             'longitude' => $service->getLongitude(),
    //         ];
    //     }, $services);

    //     return $this->json($data);
    // }

    #[Route('/api/services', name: 'api_services', methods: ['GET'])]
    public function getServices(ServiceRepository $serviceRepository, Request $request): JsonResponse
    {
        $type = $request->query->get('type'); 

        // If a type filter is provided, fetch services of that type
        if ($type) {
            $services = $serviceRepository->findBy(['type' => $type]);
        } else {
            // Fetch all services if no filter is applied
            $services = $serviceRepository->findAll();
        }

        // Format the service data for JSON response
        $data = array_map(function ($service) {
            return [
                'id' => $service->getId(),
                'name' => $service->getName(),
                'type' => $service->getType(),
                'address' => $service->getAddress(),
                'phone' => $service->getPhone(),
                'website' => $service->getWebsite(),
                'latitude' => $service->getLatitude(),
                'longitude' => $service->getLongitude(),
            ];
        }, $services);

        return $this->json($data);
    }
}
