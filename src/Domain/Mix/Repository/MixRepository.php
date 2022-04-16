<?php

namespace App\Domain\Mix\Repository;

use App\Domain\Mix\Entity\Mix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mix|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mix|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mix[]    findAll()
 * @method Mix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mix::class);
    }
    
    // /**
    //  * @return Mix[] Returns an array of Mix objects
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
    public function findOneBySomeField($value): ?Mix
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
