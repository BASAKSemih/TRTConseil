<?php

declare(strict_types=1);

namespace App\Tests\ConsultantTest;

use App\Entity\Candidate;
use App\Entity\PostJobOffer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class ConsultantConfirmJobOfferTest extends WebTestCase
{
    public function testConfirmPostJobOffer(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_consultant_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'consultant@info.fr',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('consultant_homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $candidateRepository = $entityManager->getRepository(Candidate::class);
        $candidate = $candidateRepository->findOneByEmail('candidat@verif.fr');
        $postJobOfferRepository = $entityManager->getRepository(PostJobOffer::class);
        $postJobOffer = $postJobOfferRepository->findOneByCandidate($candidate);

        $crawler = $client->request(Request::METHOD_GET, $router->generate('consultant_confirm_postjobOffer', [
            'idPostJobOffer' => $postJobOffer->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('consultant_show_all_jobOffer');
    }

}