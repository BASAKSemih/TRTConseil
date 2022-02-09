<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Admin\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdminFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new Admin();
        $admin
            ->setFirstName('admin firstname')
            ->setLastName('adminlastname')
            ->setEmail('admin@admin.com')
            ->setPassword($this->userPasswordHasher->hashPassword($admin, '12'));

        $manager->persist($admin);
        $manager->flush();

    }
}