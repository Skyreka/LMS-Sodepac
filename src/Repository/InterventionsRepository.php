<?php

namespace App\Repository;

use App\Entity\Interventions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Interventions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interventions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interventions[]    findAll()
 * @method Interventions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interventions::class);
    }

    public function findIfInterventionExist( $nameOfIntervention )
    {
        return $this->createQueryBuilder('s')
            ->andWhere( 's.type = :type' )
            ->setParameter('type', $nameOfIntervention)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByTypeAndCulture( $type, $culture )
    {
        return $this->createQueryBuilder('i')
            ->where('i.culture = :culture')
            ->andWhere('i.type = :type')
            ->setParameter('type', $type)
            ->setParameter('culture', $culture)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Interventions[] Returns an array of Interventions objects
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
    public function findOneBySomeField($value): ?Interventions
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
