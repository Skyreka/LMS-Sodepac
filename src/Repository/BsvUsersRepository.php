<?php

namespace App\Repository;

use App\Entity\BsvUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BsvUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method BsvUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method BsvUsers[]    findAll()
 * @method BsvUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BsvUsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BsvUsers::class);
    }

    // /**
    //  * @return BsvUsers[] Returns an array of BsvUsers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BsvUsers
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}