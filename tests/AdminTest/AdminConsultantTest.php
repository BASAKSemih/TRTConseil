<?php

namespace App\Tests\AdminTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class AdminConsultantTest extends WebTestCase
{
    public function testAdminCreateConsultant(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_admin_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@admin.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('admin_homePage');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('admin_create_consultant'));
        $form = $crawler->filter('form[name=consultant]')->form([
            'consultant[email]' => 'consultant@user.com',
            'consultant[firstName]' => 'John',
            'consultant[lastName]' => 'Doe',
            'consultant[password][first]' => 'password',
            'consultant[password][second]' => 'password',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('admin_homePage');
    }
}
