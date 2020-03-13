<?php

namespace App\Repository;

use App\Entity\Cultures;
use App\Entity\Ilots;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Cultures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cultures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cultures[]    findAll()
 * @method Cultures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CulturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cultures::class);
    }

    public function findCulturesByExploitation( $exploitation )
    {
        return $this->createQueryBuilder('c')
            ->leftJoin(Ilots::class, 'i', 'WITH', 'i.id = c.ilot')
            ->andWhere( 'i.exploitation = :exp')
            ->setParameter('exp', $exploitation)
            ->distinct( true )
            ->getQuery()
            ->getResult()
        ;

    }

    // /**
    //  * @return Cultures[] Returns an array of Cultures objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cultures
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
