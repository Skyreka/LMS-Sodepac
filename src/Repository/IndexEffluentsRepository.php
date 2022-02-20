<?php

namespace App\Repository;

use App\Entity\IndexEffluents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IndexEffluents|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexEffluents|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexEffluents[]    findAll()
 * @method IndexEffluents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexEffluentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndexEffluents::class);
    }


    // /**
    //  * @return IndexEffluents[] Returns an array of IndexEffluents objects
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
    public function findOneBySomeField($value): ?IndexEffluents
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
