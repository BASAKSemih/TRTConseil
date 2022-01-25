<?php

declare(strict_types=1);

namespace App\Controller\Recruiter;

use App\Entity\Recruiter\Recruiter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'recruiter_')]
final class RecruiterHomeController extends AbstractController
{
    #[Route('/espace-recruteur', name: 'homePage')]
    public function homePage(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Erreur');

            return $this->redirectToRoute('homePage');
        }
        if (!$user instanceof Recruiter) {
            $this->addFlash('error', 'Erreur 2');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('recruiter/home.html.twig');
    }
}
