<?php

namespace App\Repository;

use App\Entity\RecommendationProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RecommendationProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecommendationProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecommendationProducts[]    findAll()
 * @method RecommendationProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecommendationProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecommendationProducts::class);
    }

    // /**
    //  * @return RecommendationProducts[] Returns an array of RecommendationProducts objects
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
    public function findOneBySomeField($value): ?RecommendationProducts
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