<?php

namespace App\Repository;

use App\Entity\IndexCanevas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IndexCanevas|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexCanevas|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexCanevas[]    findAll()
 * @method IndexCanevas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexCanevasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndexCanevas::class);
    }

    public function findAllCanevas()
    {
        return $this->createQueryBuilder('i');
    }

    // /**
    //  * @return IndexCanevas[] Returns an array of IndexCanevas objects
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
    public function findOneBySomeField($value): ?IndexCanevas
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
