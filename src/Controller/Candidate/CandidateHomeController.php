<?php

declare(strict_types=1);

namespace App\Controller\Candidate;

use App\Repository\Recruiter\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'candidate_')]
final class CandidateHomeController extends AbstractController
{
    public function __construct(protected JobOfferRepository $jobOfferRepository)
    {
    }

    #[Route('/espace-candidat', name: 'homePage')]
    public function homePage(): Response
    {
        $user = $this->getUser();
        $jobOffers = $this->jobOfferRepository->findByIsVerified(true);
        if (!$user) {
            $this->addFlash('error', 'Erreur');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('candidate/home.html.twig', [
            'jobOffers' => $jobOffers,
        ]);
    }

    #[Route('/espace-candidat/postuler/{IdJobOffer}', name: 'post_jobOffer')]
    public function postJobOffer(int $IdJobOffer): RedirectResponse
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Erreur');

            return $this->redirectToRoute('homePage');
        }
        $jobOffer = $this->jobOfferRepository->findOneById($IdJobOffer);
        if (!$jobOffer) {
            $this->addFlash('warning', "Cette offre emploi n'existe pas");

            return $this->redirectToRoute('candidate_homePage');
        }
        if ($jobOffer->getIsVerified() === false) {
            $this->addFlash('warning', "Cette offre emploi n'est pas vérifier veuillez patienter");

            return $this->redirectToRoute('candidate_homePage');
        }

    }
}
