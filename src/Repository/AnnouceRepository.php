<?php

namespace App\Repository;

use App\Entity\Recruiter\Annouce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annouce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annouce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annouce[]    findAll()
 * @method Annouce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnouceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annouce::class);
    }

    // /**
    //  * @return Annouce[] Returns an array of Annouce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Annouce
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
