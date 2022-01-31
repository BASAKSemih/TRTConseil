<?php

declare(strict_types=1);

namespace App\Repository\Recruiter;

use App\Entity\Recruiter\JobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method               findAll()                                                                     array<int, JobOffer>
 * @method               findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, JobOffer>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<JobOffer>
 */
final class JobOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobOffer::class);
    }
}
