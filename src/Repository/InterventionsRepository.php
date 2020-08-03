<?php

namespace App\Repository;

use App\Entity\Cultures;
use App\Entity\Interventions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

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

    /**
     * @param $nameOfIntervention
     * @param $culture
     * @return mixed
     */
    public function findIfInterventionExist( $nameOfIntervention, $culture )
    {
        return $this->createQueryBuilder('s')
            ->where( 's.type = :type' )
            ->andWhere( 's.culture = :culture' )
            ->setParameter('type', $nameOfIntervention)
            ->setParameter('culture', $culture)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $type
     * @param $culture
     * @return mixed
     */
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

    /**
     * @param $type
     * @param $culture
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findPhyto( $culture )
    {
        return $this->createQueryBuilder('i')
            ->where('i.culture = :culture')
            ->andWhere('i.type IN (:type)')
            ->setParameter('culture', $culture)
            ->setParameter('type', ['Fongicide', 'Désherbant', 'Fongicide', 'Insecticide', 'Fertilisant', 'Nutrition'], Connection::PARAM_STR_ARRAY)
            ->orderBy('i.intervention_at', 'DESC')
            ->setMaxResults( 1 )
            ->getQuery()
            ->getOneOrNullResult()
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
