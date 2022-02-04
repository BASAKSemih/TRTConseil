<?php

declare(strict_types=1);

namespace App\Controller\Candidate\Security;

use App\Entity\Candidate;
use App\Form\Candidate\CandidateType;
use App\Repository\Candidate\CandidateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'candidate_')]
final class RegistrationController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected CandidateRepository $candidateRepository,
        protected UserPasswordHasherInterface $passwordHasher
    ) {
    }

    #[Route('/espace-candidat/inscription', name: 'security_register')]
    public function register(Request $request): Response
    {
        if ($this->getUser()) {
            $this->addFlash('warning', 'Vous êtes connecter, vous ne pouvez pas vous inscrire');

            return $this->redirectToRoute('homePage');
        }
        $candidate = new Candidate();
        $form = $this->createForm(CandidateType::class, $candidate)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $checkEmail = $this->candidateRepository->findOneByEmail($candidate->getEmail());
            if (!$checkEmail) {
                $cv = $form->get('cvPath')->getData();
                $file = md5(uniqid()).'.'.$cv->guessExtension();
                $cv->move(
                    $this->getParameter('cv_directory'),
                    $file
                );
                $candidate->setCvPath($file);
                $passwordHash = $this->passwordHasher->hashPassword($candidate, $candidate->getPassword());
                $candidate->setPassword($passwordHash);
                $this->entityManager->persist($candidate);
                $this->entityManager->flush();
                $this->addFlash('success', 'Votre compte à bien été crée un consultant va valider votre compte ');

                return $this->redirectToRoute('homePage');
            }
            $this->addFlash('warning', 'Cette adresse email est déjà utiliser');
            $form = $this->createForm(CandidateType::class, $candidate)->handleRequest($request);

            return $this->render('candidate/security/register.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->render('candidate/security/register.html.twig', [
                'form' => $form->createView(),
        ]);
    }
}
