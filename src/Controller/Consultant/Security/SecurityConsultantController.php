<?php

declare(strict_types=1);

namespace App\Controller\Consultant\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(name: 'security_consultant_')]
final class SecurityConsultantController extends AbstractController
{
    #[Route('/espace-consultant/connexion', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('warning', 'Vous êtes déjà connecter');

            return $this->redirectToRoute('consultant_homePage');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('consultant/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/espace-consultant/deconnexion', name: 'logout')]
    public function logout(): RedirectResponse
    {
        $this->addFlash('success', 'Vous avez été déconnecter');

        return $this->redirectToRoute('homePage');
    }
}
