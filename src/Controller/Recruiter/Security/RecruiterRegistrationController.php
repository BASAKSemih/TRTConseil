<?php

declare(strict_types=1);

namespace App\Controller\Recruiter\Security;

use App\Entity\Recruiter\Recruiter;
use App\Form\Recruiter\RecruiterType;
use App\Repository\Recruiter\RecruiterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'recruiter_')]
final class RecruiterRegistrationController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected RecruiterRepository $recruiterRepository,
        protected UserPasswordHasherInterface $passwordHasher
    ) {
    }

    #[Route('/espace-recruteur/inscription', name: 'security_register')]
    public function register(Request $request): Response
    {
        if ($this->getUser()) {
            $this->addFlash('warning', 'Vous êtes connecter, vous ne pouvez pas vous inscrire');

            return $this->redirectToRoute('homePage');
        }
        $recruiter = new Recruiter();
        $form = $this->createForm(RecruiterType::class, $recruiter)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $checkEmail = $this->recruiterRepository->findOneByEmail($recruiter->getEmail());
            if (!$checkEmail) {
                $passwordHash = $this->passwordHasher->hashPassword($recruiter, $recruiter->getPassword());
                $recruiter->setPassword($passwordHash);
                $this->entityManager->persist($recruiter);
                $this->entityManager->flush();
                $this->addFlash('success', 'Votre compte à bien été crée un consultant va valider votre compte ');

                return $this->redirectToRoute('homePage');
            }
            $this->addFlash('warning', 'Cette adresse email est déjà utiliser');
            $form = $this->createForm(RecruiterType::class, $recruiter)->handleRequest($request);

            return $this->render('recruiter/security/register.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->render('recruiter/security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
