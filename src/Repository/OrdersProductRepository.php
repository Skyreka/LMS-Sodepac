<?php

namespace App\Repository;

use App\Entity\OrdersProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrdersProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdersProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdersProduct[]    findAll()
 * @method OrdersProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdersProduct::class);
    }

    // /**
    //  * @return CartProduct[] Returns an array of CartProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CartProduct
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}