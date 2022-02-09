<?php

declare(strict_types=1);

namespace App\Tests\AdminTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class SecurityTest extends WebTestCase
{
    public function testLoginAdmin(): void
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
    }
}
