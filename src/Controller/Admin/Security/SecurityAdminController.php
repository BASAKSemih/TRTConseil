<?php

declare(strict_types=1);

namespace App\Controller\Admin\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(name: 'security_admin_')]
final class SecurityAdminController extends AbstractController
{
    #[Route('/espace-administrateur/connexion', name: 'login')]
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

    #[Route('/espace-administrateur/deconnexion', name: 'logout')]
    public function logout(): RedirectResponse
    {
        $this->addFlash('success', 'Vous avez été déconnecter');

        return $this->redirectToRoute('homePage');
    }
}
