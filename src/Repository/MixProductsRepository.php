<?php

namespace App\Repository;

use App\Entity\MixProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MixProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method MixProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method MixProducts[]    findAll()
 * @method MixProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MixProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MixProducts::class);
    }

    // /**
    //  * @return MixProducts[] Returns an array of MixProducts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MixProducts
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
