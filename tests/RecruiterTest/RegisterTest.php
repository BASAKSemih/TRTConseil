<?php

namespace App\Tests\RecruiterTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class RegisterTest extends WebTestCase
{
    public function testSuccessFullRegistrationRecruiter(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('recruiter_security_register'));
        self::assertRouteSame('recruiter_security_register');
        $form = $crawler->filter('form[name=recruiter]')->form([
            'recruiter[email]' => 'user@user.com',
            'recruiter[firstName]' => 'John',
            'recruiter[lastName]' => 'Doe',
            'recruiter[password][first]' => 'password',
            'recruiter[password][second]' => 'password',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testSameEmailRegistrationRecruiter(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('recruiter_security_register'));
        self::assertRouteSame('recruiter_security_register');
        $form = $crawler->filter('form[name=recruiter]')->form([
            'recruiter[email]' => 'user@user.com',
            'recruiter[firstName]' => 'John',
            'recruiter[lastName]' => 'Doe',
            'recruiter[password][first]' => 'password',
            'recruiter[password][second]' => 'password',
        ]);
        $client->submit($form);
        self::assertRouteSame('recruiter_security_register');
    }

    public function testSameEmailRegistrationCorrectRecruiter(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('recruiter_security_register'));
        self::assertRouteSame('recruiter_security_register');
        $form = $crawler->filter('form[name=recruiter]')->form([
            'recruiter[email]' => 'user@user.com',
            'recruiter[firstName]' => 'John',
            'recruiter[lastName]' => 'Doe',
            'recruiter[password][first]' => 'password',
            'recruiter[password][second]' => 'password',
        ]);
        $client->submit($form);
        self::assertRouteSame('recruiter_security_register');
        $form = $crawler->filter('form[name=recruiter]')->form([
            'recruiter[email]' => 'user@user2.com',
            'recruiter[firstName]' => 'John',
            'recruiter[lastName]' => 'Doe',
            'recruiter[password][first]' => 'password',
            'recruiter[password][second]' => 'password',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
