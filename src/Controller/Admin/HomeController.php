<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Admin\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'admin_')]
final class HomeController extends AbstractController
{
    #[Route('/espace-administrateur', name: 'homePage')]
    public function homePage(): Response
    {
        /** @var Admin $admin */
        $admin = $this->getUser();
        /* @phpstan-ignore-next-line  */
        if (!$admin) {
            $this->addFlash('error', 'Erreur');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('admin/home.html.twig');
    }

}