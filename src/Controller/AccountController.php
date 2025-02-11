<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AccountController extends AbstractController
{
    private $security;

    public function __construct(SecurityBundleSecurity $security)
    {
        $this->security = $security;
    }

    #[Route('/account', name: 'app_account')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Récupérer l'utilisateur connecté
        $user = $this->security->getUser();

        // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
        if (!$user) {
            return $this->redirectToRoute('app_account'); // Redirection vers la page de connexion
        }

        // Créer le formulaire de modification des informations
        $form = $this->createForm(AccountFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Ajouter un message de succès
            $this->addFlash('success', 'Vos informations ont été mises à jour avec succès.');

            // Rediriger vers la page "Mon Compte"
            return $this->redirectToRoute('app_account');
        }


        // Afficher la page "Mon Compte"
        return $this->render('account/index.html.twig', [
            'accountForm' => $form->createView(),
            'user' => $user

        ]);
    }
}
