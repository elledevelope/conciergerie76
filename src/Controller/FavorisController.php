<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Repository\FavorisRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FavorisController extends AbstractController
{
    #[Route('/favoris', name: 'app_favoris')]
    public function index(ServiceRepository $serviceRepository, EntityManagerInterface $entityManager): Response
    {


        if (isset($_GET['id'])) {
            $idservice = $_GET['id'];
            $service = $serviceRepository->findOneBy(["id" => $idservice]);
            $user = $this->getUser();
            $favoris = new Favoris;
            $favoris->setService($service);
            $favoris->setUser($user);



            $entityManager->persist($favoris);
            $entityManager->flush();
        }

        return $this->render('favoris/index.html.twig', [
            'controller_name' => 'FavorisController',
        ]);
    }

    #[Route('/fav', name: 'app_fav')]
    public function display(FavorisRepository $favorisRepository, ServiceRepository $serviceRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        $favorites = $favorisRepository->findBy(["user" => $user]);

      
        $utilisateur = "CEPPIC";



        // Récupérer tous les services disponibles
        $services = $serviceRepository->findAll();

        return $this->render('favoris/display.html.twig', [
            'favorites' => $favorites,
            'services' => $services,
            'utilisateur' => $utilisateur
        ]);
    }
}
