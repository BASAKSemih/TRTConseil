<?php

declare(strict_types=1);

namespace App\Controller\Recruiter;

use App\Entity\Recruiter\JobOffer;
use App\Entity\Recruiter\Recruiter;
use App\Form\Recruiter\JobOfferType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'recruiter_')]
final class RecruiterHomeController extends AbstractController
{
    #[Route('/espace-recruteur', name: 'homePage')]
    public function homePage(): Response
    {
        /** @var Recruiter $recruiter */
        $recruiter = $this->getUser();
        /** @phpstan-ignore-next-line  */
        if (!$recruiter) {
            $this->addFlash('error', 'Erreur');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('recruiter/home.html.twig');
    }

    #[Route('/espace-recruteur/cree-une-offre-emploi', name: 'create_jobOffer')]
    public function createJobOffer(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var Recruiter $recruiter */
        $recruiter = $this->getUser();
        /** @phpstan-ignore-next-line  */
        if (!$recruiter) {
            $this->addFlash('error', 'Erreur');

            return $this->redirectToRoute('homePage');
        }
        $jobOffer = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $jobOffer)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $jobOffer->setRecruiter($recruiter);
            $entityManager->persist($jobOffer);
            $entityManager->flush();
            $this->addFlash('success', "l'annonce d'emploie à bien été crée");
            return $this->redirectToRoute('recruiter_homePage');
        }
        return $this->render('recruiter/jobOffer/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
