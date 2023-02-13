<?php

namespace App\Domain\Catalogue\Repository;

use App\Domain\Catalogue\Entity\CanevasIndex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CanevasIndexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CanevasIndex::class);
    }

    public function findAllCanevas()
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.isActive = 1')
            ->orderBy('i.name', 'ASC');
    }

    // /**
    //  * @return IndexCanevas[] Returns an array of IndexCanevas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IndexCanevas
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
