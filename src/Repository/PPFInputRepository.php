<?php

namespace App\Repository;

use App\Entity\PPFInput;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PPFInput|null find($id, $lockMode = null, $lockVersion = null)
 * @method PPFInput|null findOneBy(array $criteria, array $orderBy = null)
 * @method PPFInput[]    findAll()
 * @method PPFInput[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PPFInputRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PPFInput::class);
    }

    // /**
    //  * @return PPFInput[] Returns an array of PPFInput objects
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
    public function findOneBySomeField($value): ?PPFInput
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
