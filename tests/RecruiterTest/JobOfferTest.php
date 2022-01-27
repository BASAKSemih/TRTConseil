<?php

namespace App\Tests\RecruiterTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class JobOfferTest extends WebTestCase
{
    public function testCreateJobOffer(): void
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
        $crawler = $client->request(Request::METHOD_GET, $router->generate('recruiter_create_jobOffer'));
        $form = $crawler->filter('form[name=job_offer]')->form([
            'job_offer[jobName]' => 'Développeur',
            'job_offer[workplace]' => 'Paris',
            'job_offer[description]' => 'lorem',
            'job_offer[salary]' => '2500€',
            'job_offer[schedule]' => '8-19h',

        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('recruiter_homePage');
    }

}