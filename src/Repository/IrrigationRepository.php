<?php

namespace App\Repository;

use App\Entity\Irrigation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Irrigation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Irrigation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Irrigation[]    findAll()
 * @method Irrigation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IrrigationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Irrigation::class);
    }

    /**
     * @param $user
     * @param null $limit
     * @return mixed
     */
    public function findByExploitation( $exploitation, $limit = null )
    {
        $query = $this->createQueryBuilder('i')
            ->andWhere('i.exploitation = :exp')
            ->setParameter('exp', $exploitation)
            ->orderBy('i.id', 'DESC')
            ;

        if ($limit != NULL) {
            $query = $query->setMaxResults( $limit );
        }

        return $query->getQuery()
            ->getResult();
    }

    /**
     * @param $exploitation
     * @param $year
     * @param $type
     * @param null $limit
     * @return mixed
     */
    public function findByExploitationYearAndType( $exploitation, $year, $type, $limit = null )
    {
        $query = $this->createQueryBuilder('i')
            ->andWhere('i.exploitation = :exp')
            ->andWhere('i.type = :type')
            ->andWhere('year(i.intervention_at) = :year')
            ->setParameter('exp', $exploitation)
            ->setParameter('type', $type)
            ->setParameter('year', $year)
            ->orderBy('i.intervention_at', 'ASC')
        ;

        if ($limit != NULL) {
            $query = $query->setMaxResults( $limit );
        }

        return $query->getQuery()
            ->getResult();
    }

    public function countTotalOfYear( $exploitation, $year, $type )
    {
        //TODO: Catch ?
        try {
            $totalSize = $this->createQueryBuilder('i')
                ->select('SUM(i.quantity)')
                ->andWhere('i.exploitation = :exp')
                ->andWhere('i.type = :type')
                ->andWhere('year(i.intervention_at) = :year')
                ->setParameter('exp', $exploitation)
                ->setParameter('type', $type)
                ->setParameter('year', $year)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
        return $totalSize;
    }

    // /**
    //  * @return Irrigation[] Returns an array of Irrigation objects
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
    public function findOneBySomeField($value): ?Irrigation
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
