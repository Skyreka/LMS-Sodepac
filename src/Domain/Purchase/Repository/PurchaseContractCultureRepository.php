<?php

namespace App\Domain\Purchase\Repository;

use App\Domain\Purchase\Entity\PurchaseContractCulture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PurchaseContractCulture|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchaseContractCulture|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchaseContractCulture[]    findAll()
 * @method PurchaseContractCulture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseContractCultureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchaseContractCulture::class);
    }
    
    // /**
    //  * @return PurchaseContractCulture[] Returns an array of PurchaseContractCulture objects
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
    public function findOneBySomeField($value): ?PurchaseContractCulture
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
