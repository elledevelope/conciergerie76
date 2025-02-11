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
        $services = $type ? $serviceRepository->findBy(['type' => $type]) : $serviceRepository->findAll();

        $data = array_map(fn($service) => [
            'id' => $service->getId(),
            'name' => $service->getName(),
            'type' => $service->getType(),
            'latitude' => $service->getLatitude(),
            'longitude' => $service->getLongitude(),
        ], $services);

        return $this->json($data);
    }

    // app_services
    #[Route('/services', name: 'app_services', methods: ['GET'])]
    public function services(): Response
    {
        return $this->render('service/gallery.html.twig');
    }

    // app_fast_food
    #[Route('/services/fast_food', name: 'app_fast_food')]
    public function showFastFood(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'fast_food']),
            'type' => 'Restauration rapide',
        ]);
    }

    // app_boulangerie
    #[Route('/services/boulangerie', name: 'app_boulangerie')]
    public function showBoulangerie(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'boulangerie']),
            'type' => 'Boulangerie',
        ]);
    }

    // app_supermarche
    #[Route('/services/supermarche', name: 'app_supermarche')]
    public function showSupermarche(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'supermarche']),
            'type' => 'Supermarché',
        ]);
    }

    // app_pharmacy
    #[Route('/services/pharmacy', name: 'app_pharmacy')]
    public function showPharmacy(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'pharmacy']),
            'type' => 'Pharmacie',
        ]);
    }

    // app_hospital
    #[Route('/services/hospital', name: 'app_hospital')]
    public function showHospital(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'hospital']),
            'type' => 'Hôpital',
        ]);
    }

    // app_cinema
    #[Route('/services/cinema', name: 'app_cinema')]
    public function showCinema(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'cinema']),
            'type' => 'Cinéma',
        ]);
    }

    // app_theatre
    #[Route('/services/theatre', name: 'app_theatre')]
    public function showTheatre(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'theatre']),
            'type' => 'Théâtre',
        ]);
    }

    // app_nightclub
    #[Route('/services/nightclub', name: 'app_nightclub')]
    public function showNightclub(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'nightclub']),
            'type' => 'Boîte de nuit',
        ]);
    }

    // app_library
    #[Route('/services/library', name: 'app_library')]
    public function showLibrary(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'library']),
            'type' => 'Bibliothèque',
        ]);
    }

    // app_cafe
    #[Route('/services/cafe', name: 'app_cafe')]
    public function showCafe(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'cafe']),
            'type' => 'Café',
        ]);
    }

    // app_museum
    #[Route('/services/museum', name: 'app_museum')]
    public function showMuseum(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'museum']),
            'type' => 'Musée',
        ]);
    }

    // app_park
    #[Route('/services/park', name: 'app_park')]
    public function showPark(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'park']),
            'type' => 'Parc',
        ]);
    }

    // app_bank
    #[Route('/services/bank', name: 'app_bank')]
    public function showBank(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'bank']),
            'type' => 'Banque',
        ]);
    }

    // app_atm
    #[Route('/services/atm', name: 'app_atm')]
    public function showAtm(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'atm']),
            'type' => 'Distributeur',
        ]);
    }

    // app_post_office
    #[Route('/services/post_office', name: 'app_post_office')]
    public function showPostOffice(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'post_office']),
            'type' => 'Bureau de poste',
        ]);
    }

    // app_school
    #[Route('/services/school', name: 'app_school')]
    public function showSchool(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'school']),
            'type' => 'École',
        ]);
    }

    // app_hotel
    #[Route('/services/hotel', name: 'app_hotel')]
    public function showHotel(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'hotel']),
            'type' => 'Hôtel',
        ]);
    }

    // app_toilets
    #[Route('/services/toilets', name: 'app_toilets')]
    public function showToilets(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'toilets']),
            'type' => 'Toilettes publiques',
        ]);
    }

    // app_drinking_water
    #[Route('/services/drinking_water', name: 'app_drinking_water')]
    public function showDrinkingWater(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/list.html.twig', [
            'places' => $serviceRepository->findBy(['type' => 'drinking_water']),
            'type' => 'Point d\'eau potable',
        ]);
    }
}
