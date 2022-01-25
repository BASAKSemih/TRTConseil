<?php

namespace App\Tests\CandidateTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class RegisterTest extends WebTestCase
{
    public function testSuccessFullRegistration(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('candidate_security_register'));

        $form = $crawler->filter('form[name=candidate]')->form([
            'candidate[email]' => 'user@user.com',
            'candidate[firstName]' => 'John',
            'candidate[lastName]' => 'Doe',
            'candidate[password][first]' => 'password',
            'candidate[password][second]' => 'password',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testSameEmailRegistration(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('candidate_security_register'));

        $form = $crawler->filter('form[name=candidate]')->form([
            'candidate[email]' => 'user@user.com',
            'candidate[firstName]' => 'John',
            'candidate[lastName]' => 'Doe',
            'candidate[password][first]' => 'password',
            'candidate[password][second]' => 'password',
        ]);
        $client->submit($form);
        self::assertRouteSame('candidate_security_register');
    }

    public function testSameEmailRegistrationCorrect(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('candidate_security_register'));

        $form = $crawler->filter('form[name=candidate]')->form([
            'candidate[email]' => 'user@user.com',
            'candidate[firstName]' => 'John',
            'candidate[lastName]' => 'Doe',
            'candidate[password][first]' => 'password',
            'candidate[password][second]' => 'password',
        ]);
        $client->submit($form);
        self::assertRouteSame('candidate_security_register');
        $form = $crawler->filter('form[name=candidate]')->form([
            'candidate[email]' => 'user@user2.com',
            'candidate[firstName]' => 'John',
            'candidate[lastName]' => 'Doe',
            'candidate[password][first]' => 'password',
            'candidate[password][second]' => 'password',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
