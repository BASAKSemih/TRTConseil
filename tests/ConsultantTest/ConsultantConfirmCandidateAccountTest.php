<?php

namespace App\Tests\ConsultantTest;

use App\Entity\Candidate\Candidate;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ConsultantConfirmCandidateAccountTest extends WebTestCase
{
    public function testConsultantShowAllNotConfirmedAccount(): void
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
        $crawler = $client->request(Request::METHOD_GET, $router->generate('consultant_show_all'));
        self::assertRouteSame('consultant_show_all');
    }

    public function testLoginCandidateWithNoVerifiedAccountForControl(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_candidate_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'candidat@info.fr',
            'password' => '12',
        ]);

        $client->submit($form);
        self::assertRouteSame('security_candidate_login');
    }

    public function testConsultantCheckAccountCandidate(): void
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
        $candidate = $candidateRepository->findOneByEmail('candidat@info.fr');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('consultant_show_candidate', [
            'idCandidate' => $candidate->getId(),
        ]));
        self::assertRouteSame('consultant_show_candidate');
    }

    public function testConsultantConfirmAccountCandidate(): void
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
        $candidate = $candidateRepository->findOneByEmail('candidat@info.fr');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('consultant_confirm_account_candidate', [
            'idCandidate' => $candidate->getId(),
        ]));
        self::assertRouteSame('consultant_confirm_account_candidate');
        $client->followRedirect();
        self::assertRouteSame('consultant_show_all');
    }

    public function testLoginCandidateWithSameAccountVerified(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_candidate_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'candidat@info.fr',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('candidate_homePage');
    }
}
