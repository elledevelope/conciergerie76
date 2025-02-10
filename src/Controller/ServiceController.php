<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/api/services', name: 'api_services', methods: ['GET'])]
    public function getServices(ServiceRepository $serviceRepository): JsonResponse
    {
        $services = $serviceRepository->findAll();

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
