<?php
// src/Controller/CguController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CguController extends AbstractController
{
    #[Route('/cgu', name: 'app_cgu')]
    public function index(): Response
    {
        return $this->render('cgu/index.html.twig');
    }
}
