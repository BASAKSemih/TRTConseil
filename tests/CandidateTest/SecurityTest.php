<?php

namespace App\Tests\CandidateTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class SecurityTest extends WebTestCase
{
    public function testLoginCandidateWithNoVerifiedAccount(): void
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

    public function testLoginCandidateWithVerifiedAccount(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_candidate_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'candidat@verif.fr',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('candidate_homePage');
    }
}
