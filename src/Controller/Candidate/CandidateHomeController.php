<?php

declare(strict_types=1);

namespace App\Controller\Candidate;

use App\Entity\Candidate;
use App\Entity\JobOffer;
use App\Entity\PostJobOffer;
use App\Repository\Recruiter\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'candidate_')]
final class CandidateHomeController extends AbstractController
{
    public function __construct(protected JobOfferRepository $jobOfferRepository, protected EntityManagerInterface $entityManager)
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

    #[Route('/espace-candidat/postuler/{idJobOffer}', name: 'post_jobOffer')]
    public function postJobOffer(int $idJobOffer): RedirectResponse
    {
        /** @var Candidate $candidate */
        $candidate = $this->getUser();
        if (!$candidate) {
            $this->addFlash('warning', 'Erreur');

            return $this->redirectToRoute('homePage');
        }
        /** @var JobOffer $jobOffer */
        $jobOffer = $this->jobOfferRepository->findOneById($idJobOffer);
        if (!$jobOffer) {
            $this->addFlash('warning', "Cette offre emploi n'existe pas");

            return $this->redirectToRoute('candidate_homePage');
        }
        if (false === $jobOffer->getIsVerified()) {
            $this->addFlash('warning', "Cette offre emploi n'est pas vérifier veuillez patienter");

            return $this->redirectToRoute('homePage');
        }
        $postJobOffers = $jobOffer->getPostJobOffers();
        /** @var PostJobOffer $item */
        foreach ($postJobOffers as $item) {
            if ($item->getCandidate() === $candidate) {
                $this->addFlash('warning', 'Vous avez déjà postulé a cette offre emploi');

                return $this->redirectToRoute('candidate_homePage');
            }
        }
        $postJobOffer = new PostJobOffer();
        $postJobOffer->setCandidate($candidate);
        $postJobOffer->setJobOffer($jobOffer);
        $this->entityManager->persist($postJobOffer);
        $this->entityManager->flush();
        $this->addFlash('success', "Vous avez postulé a l'offre emploi");

        return $this->redirectToRoute('candidate_homePage');
    }
}
