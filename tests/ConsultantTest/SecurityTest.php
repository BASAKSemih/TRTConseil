<?php

namespace App\Tests\ConsultantTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class SecurityTest extends WebTestCase
{
    public function testLoginConsultant(): void
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
    }

    public function testLoginWithCandidateAccount(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_consultant_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'candidat@verif.fr',
            'password' => '12',
        ]);

        $client->submit($form);
        self::assertRouteSame('security_consultant_login');
    }

    public function testLoginWithRecruiterAccount(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_consultant_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'recruteur@verif.fr',
            'password' => '12',
        ]);

        $client->submit($form);
        self::assertRouteSame('security_consultant_login');
    }
}
