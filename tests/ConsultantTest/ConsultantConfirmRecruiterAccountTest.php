<?php

declare(strict_types=1);

namespace App\Tests\ConsultantTest;

use App\Entity\Recruiter\Recruiter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class ConsultantConfirmRecruiterAccountTest extends WebTestCase
{
    public function testLoginRecruiterWithNotVerifiedAccountCheck(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_recruiter_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'recruteur@info.fr',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('security_recruiter_login');
    }

    public function testConsultantCheckRecruiterAccount(): void
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
        $recruiterRepository = $entityManager->getRepository(Recruiter::class);
        $recruiter = $recruiterRepository->findOneByEmail('recruteur@info.fr');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('consultant_show_recruiter', [
            'idRecruiter' => $recruiter->getId(),
        ]));
        self::assertRouteSame('consultant_show_recruiter');
    }

    public function testConsultantConfirmRecruiterAccount(): void
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
        $recruiterRepository = $entityManager->getRepository(Recruiter::class);
        $recruiter = $recruiterRepository->findOneByEmail('recruteur@info.fr');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('consultant_confirm_account_recruiter', [
            'idRecruiter' => $recruiter->getId(),
        ]));
        self::assertRouteSame('consultant_confirm_account_recruiter');
        $client->followRedirect();
        self::assertRouteSame('consultant_show_all');
    }

    public function testLoginRecruiterAccountWithVerifiedAccountByConsultant(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_recruiter_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'recruteur@info.fr',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('recruiter_homePage');
    }
}
