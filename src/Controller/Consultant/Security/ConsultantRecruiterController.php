<?php

declare(strict_types=1);

namespace App\Controller\Consultant\Security;

use App\Repository\Candidate\CandidateRepository;
use App\Repository\Recruiter\RecruiterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'consultant_')]
final class ConsultantRecruiterController extends AbstractController
{
    public function __construct(protected RecruiterRepository $recruiterRepository, protected EntityManagerInterface $entityManager)
    {
    }

    #[Route('/espace-consultant/voir-le-profil-recruteur/{idRecruiter}', name: 'show_recruiter')]
    public function showRecruiterProfil(int $idRecruiter): Response
    {
        $recruiter = $this->recruiterRepository->findOneById($idRecruiter);
        if (!$recruiter) {
            $this->addFlash('warning', "Ce compte recruiter n'existe pas");

            return $this->redirectToRoute('consultant_show_all');
        }

        return $this->render('consultant/recruiter/show.html.twig', [
            'recruiter' => $recruiter,
        ]);
    }

    #[Route('/espace-consultant/confirmer-inscription-recruteur/{idRecruiter}', name: 'confirm_account_recruiter')]
    public function confirmRecruiterAccount(int $idRecruiter): RedirectResponse
    {
        $recruiter = $this->recruiterRepository->findOneById($idRecruiter);
        if (!$recruiter) {
            $this->addFlash('warning', "Ce compte recruiter n'existe pas");

            return $this->redirectToRoute('consultant_show_all');
        }
        $recruiter->setIsVerified(true);
        $this->entityManager->flush();
        $this->addFlash('success', 'Le compte recruteur à bien été vérifier');

        return $this->redirectToRoute('consultant_show_all');
    }
}