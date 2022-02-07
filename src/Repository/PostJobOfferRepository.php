<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PostJobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostJobOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostJobOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostJobOffer[]    findAll()                                                   array<int, PostJobOffer>
 * @method PostJobOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, PostJobOffer>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<PostJobOffer>
 */
final class PostJobOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostJobOffer::class);
    }

    // /**
    //  * @return PostJobOffer[] Returns an array of PostJobOffer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostJobOffer
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
