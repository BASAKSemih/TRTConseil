<?php

declare(strict_types=1);

namespace App\Repository\Recruiter;

use App\Entity\Recruiter\Recruiter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Recruiter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recruiter|null findOneBy(array $criteria, array $orderBy = null)
 * @method                findAll()                                                                     array<int, Recruiter>
 * @method                findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, Recruiter>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<Recruiter>
 */
final class RecruiterRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recruiter::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Recruiter) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
