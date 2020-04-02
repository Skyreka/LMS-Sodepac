<?php

namespace App\Repository;

use App\Entity\Recommendations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Recommendations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recommendations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recommendations[]    findAll()
 * @method Recommendations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecommendationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recommendations::class);
    }

    // /**
    //  * @return Recommendations[] Returns an array of Recommendations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recommendations
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
