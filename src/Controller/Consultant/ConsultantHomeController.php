<?php

declare(strict_types=1);

namespace App\Controller\Consultant;

use App\Repository\Candidate\CandidateRepository;
use App\Repository\PostJobOfferRepository;
use App\Repository\Recruiter\JobOfferRepository;
use App\Repository\Recruiter\RecruiterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'consultant_')]
final class ConsultantHomeController extends AbstractController
{
    public function __construct(
        protected CandidateRepository $candidateRepository,
        protected RecruiterRepository $recruiterRepository,
        protected JobOfferRepository $jobOfferRepository,
        protected EntityManagerInterface $entityManager,
        protected PostJobOfferRepository $postJobRepository
    ) {
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

    #[Route('/espace-consultant/page-des-informations-non-verifier', name: 'show_all')]
    public function showAllNoVerifiedUser(): Response
    {
        $candidates = $this->candidateRepository->findByIsVerified(false);
        $recruiters = $this->recruiterRepository->findByIsVerified(false);
        $jobOffers = $this->jobOfferRepository->findByIsVerified(false);
        $postJobOffers = $this->postJobRepository->findByIsVerified(false);

        return $this->render('consultant/showAll.html.twig', [
            'candidates' => $candidates,
            'recruiters' => $recruiters,
            'jobOffers' => $jobOffers,
            'postJobOffers' => $postJobOffers,
        ]);
    }

    #[Route('/espace-consultant/liste-des-emploies-non-verifier', name: 'show_all_jobOffer')]
    public function showAllNoVerifiedJob(): Response
    {
        $jobOffers = $this->jobOfferRepository->findByIsVerified(false);

        return $this->render('consultant/JobOffer/showAll.html.twig', [
            'jobOffers' => $jobOffers,
        ]);
    }

    #[Route('/espace-consultant/voir-le-poste/{idJobOffer}', name: 'show_jobOffer')]
    public function showJobOffer(int $idJobOffer): Response
    {
        $jobOffer = $this->jobOfferRepository->findOneById($idJobOffer);
        if (!$jobOffer) {
            $this->addFlash('warning', "Cette offre emploi n'existe pas n'existe pas");

            return $this->redirectToRoute('consultant_show_all');
        }

        return $this->render('consultant/JobOffer/show.html.twig', [
            'jobOffer' => $jobOffer,
        ]);
    }

    #[Route('/espace-consultant/confirmer-le-poste/{idJobOffer}', name: 'confirm_jobOffer')]
    public function confirmJobOffer(int $idJobOffer): RedirectResponse
    {
        $jobOffer = $this->jobOfferRepository->findOneById($idJobOffer);
        if (!$jobOffer) {
            $this->addFlash('warning', "Cette offre emploi n'existe pas n'existe pas");

            return $this->redirectToRoute('consultant_show_all_jobOffer');
        }
        $jobOffer->setIsVerified(true);
        $this->entityManager->flush();
        $this->addFlash('success', "l'offre emploie à bien été vérifier");

        return $this->redirectToRoute('consultant_show_all_jobOffer');
    }

    #[Route('/espace-consultant/confirmer-la-candidate/{idPostJobOffer}', name: 'confirm_postjobOffer')]
    public function confirmPostJobOffer(int $idPostJobOffer): RedirectResponse
    {
        $postJobOffer = $this->postJobRepository->findOneById($idPostJobOffer);
        if (!$postJobOffer) {
            $this->addFlash('warning', "Cette candidature n'existe pas n'existe pas");

            return $this->redirectToRoute('consultant_show_all_jobOffer');
        }
        if (true === $postJobOffer->getIsVerified()) {
            $this->addFlash('warning', 'Cette candidature à déjà été vérifier');

            return $this->redirectToRoute('consultant_show_all_jobOffer');
        }
        $postJobOffer->setIsVerified(true);
        $this->entityManager->flush();
        $this->addFlash('success', "l'offre emploi à bien été vérifier");

        return $this->redirectToRoute('consultant_show_all_jobOffer');
    }
}
