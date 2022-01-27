<?php

declare(strict_types=1);

namespace App\Controller\Recruiter;

use App\Entity\Recruiter\Company;
use App\Entity\Recruiter\Recruiter;
use App\Form\Recruiter\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route(name: 'recruiter_')]
final class RecruiterHomeController extends AbstractController
{
    public function __construct(protected EntityManagerInterface $entityManager, protected SluggerInterface $slugger)
    {
    }

    #[Route('/espace-recruteur', name: 'homePage')]
    public function homePage(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Erreur');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('recruiter/home.html.twig');
    }

    #[Route('/espace-recruteur/cree-la-page-entreprise', name: 'create_company')]
    public function createCompany(Request $request): Response
    {
        /** @var Recruiter $recruiter */
        $recruiter = $this->getUser();
        if (!$recruiter) {
            $this->addFlash('error', 'Vous devez êtres connecter pour crée la page de votre entreprise');

            return $this->redirectToRoute('security_recruiter_login');
        }
        if ($recruiter->getCompany())
        {
            $this->addFlash('error', 'Vous avez déjà crée la page de votre entreprise');

            return $this->redirectToRoute('recruiter_homePage');
        }

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $company->setSlug((string)$this->slugger->slug($company->getName()));
            $company->setRecruiter($recruiter);
            $this->entityManager->persist($company);
            $this->entityManager->flush();
            $this->addFlash('success', "Votre page d'entreprise a bien été crée");
            return $this->redirectToRoute('recruiter_homePage');
        }
        return $this->render('recruiter/company/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
