<?php
namespace App\Controller;

use App\Service\OverpassService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OverpassController extends AbstractController
{
    private $overpassService;

    public function __construct(OverpassService $overpassService)
    {
        $this->overpassService = $overpassService;
    }

    /**
     * @Route("/scrape", name="scrape_overpass_data")
     */
    public function scrape()
    {
        // Define the bounding box coordinates for Rouen, France
        $minLat = 49.4;  // Min Latitude
        $minLon = 1.05;  // Min Longitude
        $maxLat = 49.5;  // Max Latitude
        $maxLon = 1.15;  // Max Longitude

        // Fetch and store the drinking water nodes in Rouen
        $this->overpassService->fetchDrinkingWaterNodes($minLat, $minLon, $maxLat, $maxLon);

        return new Response('Data has been scraped and saved successfully for Rouen!');
    }
}
