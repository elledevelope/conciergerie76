<?php

namespace App\Controller;

use App\Repository\DrinkingWaterNodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MapController extends AbstractController
{
    #[Route('/map', name: 'app_map')]
    public function index(DrinkingWaterNodeRepository $repository): Response
    {
        // Fetch all drinking water nodes from the database
        $nodes = $repository->findAll();

        // Extract coordinates and other necessary data to pass to the Twig template
        $nodeData = [];
        foreach ($nodes as $node) {
            $nodeData[] = [
                'lat' => $node->getLat(),
                'lon' => $node->getLon(),
                'name' => $node->getName(),
            ];
        }

        return $this->render('map/index.html.twig', [
            'controller_name' => 'MapController',
            'nodes' => $nodeData, // Pass the nodes data to the template
        ]);
    }
}

