<?php

namespace App\Tests\CandidateTest;

use App\Entity\JobOffer;
use App\Repository\Recruiter\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class PostJobOfferTest extends WebTestCase
{
    public function testCandidatePostJobOfferWithNotVerifiedJobOffer(): void
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
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        /** @var JobOfferRepository $jobOfferRepository */
        $jobOfferRepository = $entityManager->getRepository(JobOffer::class);
        /** @var JobOffer $jobOffer */
        $jobOffer = $jobOfferRepository->findOneByJobName('test');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('candidate_post_jobOffer', [
            'idJobOffer' => $jobOffer->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCandidatePostJobbOfferWithVerifiedJobOffer(): void
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
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        /** @var JobOfferRepository $jobOfferRepository */
        $jobOfferRepository = $entityManager->getRepository(JobOffer::class);
        /** @var JobOffer $jobOffer */
        $jobOffer = $jobOfferRepository->findOneByJobName('verifierd');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('candidate_post_jobOffer', [
            'idJobOffer' => $jobOffer->getId(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('candidate_homePage');
    }
}
