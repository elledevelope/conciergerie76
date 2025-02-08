<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class MapController extends AbstractController
{
    #[Route('/map', name: 'app_map')]
    public function index(): Response
    {
        return $this->render('map/index.html.twig', [
            'controller_name' => 'MapController',
        ]);
    }

    #[Route('/set-location', name: 'set_location', methods: ['POST'])]
    public function setLocation(Request $request, SessionInterface $session): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['latitude']) && isset($data['longitude'])) {
            $session->set('user_latitude', $data['latitude']);
            $session->set('user_longitude', $data['longitude']);
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error'], 400);
    }
}
