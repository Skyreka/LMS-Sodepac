<?php

namespace App\Repository;

use App\Entity\PurchaseContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PurchaseContract|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchaseContract|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchaseContract[]    findAll()
 * @method PurchaseContract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchaseContract::class);
    }

    // /**
    //  * @return PurchaseContract[] Returns an array of PurchaseContract objects
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
    public function findOneBySomeField($value): ?PurchaseContract
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
