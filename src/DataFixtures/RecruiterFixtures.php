<?php

namespace App\DataFixtures;

use App\Entity\Recruiter\Recruiter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RecruiterFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $recruiter1 = new Recruiter();
        $recruiter1
            ->setEmail('recruteur@info.fr')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setPassword($this->userPasswordHasher->hashPassword($recruiter1, '12'));

        $manager->persist($recruiter1);

        $recruiter2 = new Recruiter();
        $recruiter2
            ->setEmail('recruteur@verif.fr')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setIsVerified(true)
            ->setPassword($this->userPasswordHasher->hashPassword($recruiter2, '12'));

        $manager->persist($recruiter2);
        $manager->flush();
    }
}
