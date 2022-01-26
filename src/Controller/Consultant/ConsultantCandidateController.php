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
final class ConsultantCandidateController extends AbstractController
{
    public function __construct(protected CandidateRepository $candidateRepository, protected EntityManagerInterface $entityManager)
    {
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

    #[Route('/espace-consultant/confirmer-inscription-candidat/{idCandidate}', name: 'confirm_account_candidate')]
    public function confirmCandidateAccount(int $idCandidate): RedirectResponse
    {
        $candidate = $this->candidateRepository->findOneById($idCandidate);
        if (!$candidate) {
            $this->addFlash('warning', "Ce compte candidat n'existe pas");

            return $this->redirectToRoute('consultant_show_all');
        }
        $candidate->setIsVerified(true);
        $this->entityManager->flush();
        $this->addFlash('success', 'Le compte candidat à bien été vérifier');

        return $this->redirectToRoute('consultant_show_all');
    }
}
