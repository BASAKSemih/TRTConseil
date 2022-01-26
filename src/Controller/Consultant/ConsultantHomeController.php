<?php

declare(strict_types=1);

namespace App\Controller\Consultant;

use App\Repository\Candidate\CandidateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'consultant_')]
final class ConsultantHomeController extends AbstractController
{
    public function __construct(protected CandidateRepository $candidateRepository)
    {
    }

    #[Route('/espace-consultant', name: 'homePage')]
    public function homePage(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Erreur');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('consultant/home.html.twig');
    }

    #[Route('/espace-consultant/liste-des-utilisateurs-non-verifier', name: 'show_all')]
    public function showAllNoVerifiedUser(): Response
    {
        $candidates = $this->candidateRepository->findByIsVerified(false);

        return $this->render('consultant/showAll.html.twig', [
            'candidates' => $candidates,
        ]);
    }
}
