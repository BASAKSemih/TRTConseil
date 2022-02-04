<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Recruiter\JobOffer;
use App\Entity\Recruiter\Recruiter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RecruiterFixtures extends Fixture
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

        $jobOffer = new JobOffer();
        $jobOffer
            ->setRecruiter($recruiter2)
            ->setDescription('test')
            ->setJobName('test')
            ->setSalary('e')
            ->setSchedule('e')
            ->setWorkplace('e');
        $manager->persist($jobOffer);
        $manager->flush();

        $jobOffer2 = new JobOffer();
        $jobOffer2
            ->setRecruiter($recruiter2)
            ->setDescription('verifierd')
            ->setJobName('verifierd')
            ->setSalary('verifierd')
            ->setSchedule('verifierd')
            ->setIsVerified(true)
            ->setWorkplace('verifierd');
        $manager->persist($jobOffer2);
        $manager->flush();
    }
}
