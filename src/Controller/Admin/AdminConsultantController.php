<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Admin;
use App\Entity\Consultant\Consultant;
use App\Form\Consultant\ConsultantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'admin_')]
class AdminConsultantController extends AbstractController
{
    #[Route('/espace-administrateur/cree-un-consultant', name: 'create_consultant')]
    public function createConsultant(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var Admin $admin */
        $admin = $this->getUser();
        /* @phpstan-ignore-next-line  */
        if (!$admin) {
            $this->addFlash('error', 'Erreur');

            return $this->redirectToRoute('homePage');
        }
        $consultant = new Consultant();
        $form = $this->createForm(ConsultantType::class, $consultant)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $passwordHash = $passwordHasher->hashPassword($consultant, $consultant->getPassword());
            $entityManager->persist($consultant);
            $entityManager->flush();
            $this->addFlash('success', 'le consultant à été crée avec succès');

            return $this->redirectToRoute('admin_homePage');
        }

        return $this->render('admin/consultant/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
