<?php

namespace App\Repository;

use App\Entity\Cultures;
use App\Entity\Ilots;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
            ->orderBy('i.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $exploitation
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAvailableSizeIlot( $exploitation )
    {
        $totalIlots = $this->createQueryBuilder('t')
            ->select('SUM(t.size)')
            ->andWhere('t.exploitation = :exp')
            ->setParameter('exp', $exploitation)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $totalExploitation = $exploitation->getSize();

        return $totalExploitation - $totalIlots;
    }

    /**
     * @param $exploitation
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countIlotByExploitation( $exploitation )
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.exploitation = :exp')
            ->setParameter('exp', $exploitation)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param $indexNameId
     * @param $exploitation
     * @param null $onlyQuery
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByIndexCulture( $indexNameId, $exploitation, $onlyQuery = null )
    {
        $query = $this->createQueryBuilder('i')
            ->leftJoin(Cultures::class, 'c', 'WITH', 'i.id = c.ilot')
            ->leftJoin( Ilots::class, 'il', 'WITH', 'il.id = c.ilot' )
            ->where('c.name = :nameId')
            ->andWhere('il.exploitation = :exploitation')
            ->setParameter('nameId', $indexNameId)
            ->setParameter('exploitation', $exploitation)
        ;

        if ($onlyQuery) {
            return $query;
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @param $indexNameId
     * @param $exploitation
     * @param null $onlyQuery
     * @return \Doctrine\ORM\QueryBuilder|mixed
     */
    public function findByIndexCultureInProgress( $indexNameId, $exploitation, $onlyQuery = null )
    {
        $query = $this->createQueryBuilder('i')
            ->leftJoin(Cultures::class, 'c', 'WITH', 'i.id = c.ilot')
            ->leftJoin( Ilots::class, 'il', 'WITH', 'il.id = c.ilot' )
            ->where('c.name = :nameId')
            ->andWhere('c.status != :status')
            ->andWhere('il.exploitation = :exploitation')
            ->setParameter('status', 1)
            ->setParameter('nameId', $indexNameId)
            ->setParameter('exploitation', $exploitation)
        ;

        if ($onlyQuery) {
            return $query;
        }

        return $query->getQuery()->getResult();
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
