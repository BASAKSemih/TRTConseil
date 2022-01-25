<?php

declare(strict_types=1);

namespace App\Controller\Candidate;

use App\Entity\Recruiter\Recruiter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'candidate_')]
final class CandidateHomeController extends AbstractController
{
    #[Route('/espace-candidat', name: 'homePage')]
    public function homePage(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Erreur');

            return $this->redirectToRoute('homePage');
        }
        return $this->render('candidate/home.html.twig');
    }
}
