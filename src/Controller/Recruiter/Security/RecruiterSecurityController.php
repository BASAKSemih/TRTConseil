<?php

namespace App\Controller\Recruiter\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(name: 'security_recruiter_')]
class RecruiterSecurityController extends AbstractController
{
    #[Route('/espace-recruteur/connexion', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('warning', 'Vous êtes déjà connecter');

            return $this->redirectToRoute('recruiter_homePage');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('recruiter/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/espace-recruteur/deconnexion', name: 'logout')]
    public function logout(): RedirectResponse
    {
        $this->addFlash('success', 'Vous avez été déconnecter');

        return $this->redirectToRoute('homePage');
    }
}
