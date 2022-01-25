<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Candidate\Candidate;
use App\Entity\Recruiter\Recruiter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CandidateFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $candidate = new Candidate();
        $candidate
            ->setEmail('candidat@info.fr')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setPassword($this->userPasswordHasher->hashPassword($candidate, '12'));

        $manager->persist($candidate);

        $candidate2 = new Candidate();
        $candidate2
            ->setEmail('candidat@verif.fr')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setIsVerified(true)
            ->setPassword($this->userPasswordHasher->hashPassword($candidate2, '12'));

        $manager->persist($candidate2);
        $manager->flush();
    }
}