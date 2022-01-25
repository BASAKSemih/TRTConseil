<?php

namespace App\Tests\RecruiterTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class SecurityTest extends WebTestCase
{
    public function testLoginRecruiterWithNoVerifiedAccount(): void
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
        self::assertRouteSame('security_recruiter_login');
    }

    public function testLoginRecruiterWithVerifiedAccount(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_recruiter_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'recruteur@verif.fr',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('recruiter_homePage');
    }

}