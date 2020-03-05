<?php

namespace App\Repository;

use App\Entity\Bsv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Bsv|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bsv|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bsv[]    findAll()
 * @method Bsv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BsvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bsv::class);
    }

    // /**
    //  * @return Bsv[] Returns an array of Bsv objects
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
    public function findOneBySomeField($value): ?Bsv
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
