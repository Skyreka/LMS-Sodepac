<?php

namespace App\Domain\PPF\Repository;

use App\Domain\PPF\Entity\PPF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PPF|null find($id, $lockMode = null, $lockVersion = null)
 * @method PPF|null findOneBy(array $criteria, array $orderBy = null)
 * @method PPF[]    findAll()
 * @method PPF[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PPFRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PPF::class);
    }
    
    // /**
    //  * @return PPF[] Returns an array of PPF objects
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
    public function findOneBySomeField($value): ?PPF
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
