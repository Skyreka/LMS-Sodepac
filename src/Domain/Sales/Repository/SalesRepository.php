<?php

namespace App\Domain\Sales\Repository;

use App\Domain\Sales\Entity\Sales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sales|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sales|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sales[]    findAll()
 * @method Sales[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sales::class);
    }
    
    /**
     * @return int|mixed|string
     */
    public function findAllSales()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.culture', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    // /**
    //  * @return Sales[] Returns an array of Sales objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    /*
    public function findOneBySomeField($value): ?Sales
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
