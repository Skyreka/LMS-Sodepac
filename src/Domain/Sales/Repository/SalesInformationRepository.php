<?php

namespace App\Domain\Sales\Repository;

use App\Domain\Sales\Entity\SalesInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SalesInformation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalesInformation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalesInformation[]    findAll()
 * @method SalesInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalesInformationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SalesInformation::class);
    }
    
    // /**
    //  * @return SalesInformation[] Returns an array of SalesInformation objects
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
    public function findOneBySomeField($value): ?SalesInformation
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
