<?php

namespace App\Repository;

use App\Entity\TicketsMessages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TicketsMessages|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketsMessages|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketsMessages[]    findAll()
 * @method TicketsMessages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketsMessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketsMessages::class);
    }

    // /**
    //  * @return TicketsMessages[] Returns an array of TicketsMessages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TicketsMessages
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
