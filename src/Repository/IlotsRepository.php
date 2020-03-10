<?php

namespace App\Repository;

use App\Entity\Ilots;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Ilots|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ilots|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ilots[]    findAll()
 * @method Ilots[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IlotsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ilots::class);
    }

    /**
    * @return Ilots[] Returns an array of Ilots objects
    */
    public function findIlotsFromUser( $exploitation )
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exploitation = :exploitation')
            ->setParameter('exploitation', $exploitation)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Ilots
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
