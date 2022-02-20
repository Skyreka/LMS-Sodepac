<?php

namespace App\Repository;

use App\Entity\IndexGrounds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IndexGrounds|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexGrounds|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexGrounds[]    findAll()
 * @method IndexGrounds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexGroundsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndexGrounds::class);
    }

    // /**
    //  * @return IndexGrounds[] Returns an array of IndexGrounds objects
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
    public function findOneBySomeField($value): ?IndexGrounds
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
