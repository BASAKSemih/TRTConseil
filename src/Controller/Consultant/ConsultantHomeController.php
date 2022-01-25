<?php

declare(strict_types=1);

namespace App\Controller\Consultant;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'consultant_')]
final class ConsultantHomeController extends AbstractController
{
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
}
