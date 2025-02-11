<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
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

    // **Page principale des services**
    #[Route('/services', name: 'app_services', methods: ['GET'])]
    public function services(): Response
    {
        return $this->render('service/gallery.html.twig');
    }




    // **Routes par catégorie de services**    
    // **Nourriture**
    #[Route('/services/nourriture', name: 'app_nourriture')]
    public function showNourriture(ServiceRepository $serviceRepository): Response
    {
        $nourriture = $serviceRepository->findBy(['type' => ['fast_food', 'bakery', 'supermarket', 'convenience', 'mall', 'shop']]);

        return $this->render('service/list.html.twig', [
            'places' => $nourriture,
            'type' => 'Nourriture',
        ]);
    }

    // **Santé**
    #[Route('/services/sante', name: 'app_sante')]
    public function showSante(ServiceRepository $serviceRepository): Response
    {
        $sante = $serviceRepository->findBy(['type' => ['pharmacy', 'hospital']]);

        return $this->render('service/list.html.twig', [
            'places' => $sante,
            'type' => 'Santé',
        ]);
    }

    // **Transport**
    #[Route('/services/transport', name: 'app_transport')]
    public function showTransport(ServiceRepository $serviceRepository): Response
    {
        $transport = $serviceRepository->findBy(['type' => ['fuel', 'parking', 'bicycle_rental']]);

        return $this->render('service/list.html.twig', [
            'places' => $transport,
            'type' => 'Transport',
        ]);
    }

    // **Loisirs**
    #[Route('/services/loisirs', name: 'app_loisirs')]
    public function showLoisirs(ServiceRepository $serviceRepository): Response
    {
        $loisirs = $serviceRepository->findBy(['type' => ['cinema', 'theatre', 'nightclub', 'library']]);

        return $this->render('service/list.html.twig', [
            'places' => $loisirs,
            'type' => 'Loisirs',
        ]);
    }

    // **Services**
    #[Route('/services/services', name: 'app_services_group')]
    public function showServices(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findBy(['type' => ['bank', 'atm', 'post_office', 'school', 'university', 'hairdresser', 'dry_cleaning', 'car_repair']]);

        return $this->render('service/list.html.twig', [
            'places' => $services,
            'type' => 'Services',
        ]);
    }

    // **Hébergement**
    #[Route('/services/hebergement', name: 'app_hebergement')]
    public function showHebergement(ServiceRepository $serviceRepository): Response
    {
        $hebergement = $serviceRepository->findBy(['type' => 'hotel']);

        return $this->render('service/list.html.twig', [
            'places' => $hebergement,
            'type' => 'Hébergement',
        ]);
    }

    // **Toilettes publiques**
    #[Route('/services/toilettes', name: 'app_toilettes')]
    public function showToilettes(ServiceRepository $serviceRepository): Response
    {
        $toilettes = $serviceRepository->findBy(['type' => 'toilets']);

        return $this->render('service/list.html.twig', [
            'places' => $toilettes,
            'type' => 'Toilettes publiques',
        ]);
    }
}
