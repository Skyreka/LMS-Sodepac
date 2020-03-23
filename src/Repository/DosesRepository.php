<?php

namespace App\Repository;

use App\Entity\Doses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Doses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Doses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Doses[]    findAll()
 * @method Doses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DosesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doses::class);
    }

    // /**
    //  * @return Doses[] Returns an array of Doses objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Doses
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
