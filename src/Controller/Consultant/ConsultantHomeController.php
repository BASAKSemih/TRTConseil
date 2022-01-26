<?php

declare(strict_types=1);

namespace App\Controller\Consultant;

use App\Repository\Candidate\CandidateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'consultant_')]
final class ConsultantHomeController extends AbstractController
{
    public function __construct(protected CandidateRepository $candidateRepository, protected EntityManagerInterface $entityManager)
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

    #[Route('/espace-consultant/voir-le-profil-candidat/{idCandidate}', name: 'show_candidate')]
    public function showCandidateProfil(int $idCandidate): Response
    {
        $candidate = $this->candidateRepository->findOneById($idCandidate);
        if (!$candidate) {
            $this->addFlash('warning', "Ce compte candidat n'existe pas");

            return $this->redirectToRoute('consultant_show_all');
        }

        return $this->render('consultant/candidate/show.html.twig', [
            'candidate' => $candidate,
        ]);
    }

    #[Route('/espace-consultant/confirmer-inscription/{idCandidate}', name: 'confirm_account_candidate')]
    public function ConfirmAccount(int $idCandidate): RedirectResponse
    {
        $candidate = $this->candidateRepository->findOneById($idCandidate);
        if (!$candidate) {
            $this->addFlash('warning', "Ce compte candidat n'existe pas");

            return $this->redirectToRoute('consultant_show_all');
        }
        $candidate->setIsVerified(true);
        $this->entityManager->flush();
        $this->addFlash('success', 'Le compte à bien été vérifier');

        return $this->redirectToRoute('consultant_show_all');
    }
}
