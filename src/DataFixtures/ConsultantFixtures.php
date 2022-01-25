<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Consultant\Consultant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ConsultantFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $consultant = new Consultant();
        $consultant
            ->setEmail('consultant@info.fr')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setPassword($this->userPasswordHasher->hashPassword($consultant, '12'));
        $manager->persist($consultant);
        $manager->flush();
    }
}
