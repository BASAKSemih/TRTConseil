<?php

namespace App\Tests\RecruiterTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class CompanyTest extends WebTestCase
{
    public function testRecruiterCreateCompany(): void
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
        $crawler = $client->request(Request::METHOD_GET, $router->generate('recruiter_create_company'));
        $form = $crawler->filter('form[name=company]')->form([
            'company[name]' => 'user@user.com',
            'company[description]' => 'John',
            'company[phoneNumber]' => 'Doe',
            'company[city]' => 'user@user.com',
            'company[region]' => 'John',
            'company[address]' => 'Doe',
            'company[postalCode]' => 'John',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('recruiter_homePage');
    }

    public function testCreateCompanyRecruiterWithAlreadyACompanyCreated(): void
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
        $crawler = $client->request(Request::METHOD_GET, $router->generate('recruiter_create_company'));
        $client->followRedirect();
        self::assertRouteSame('recruiter_homePage');
    }

}