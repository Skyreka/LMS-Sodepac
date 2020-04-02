<?php

namespace App\Repository;

use App\Entity\Cultures;
use App\Entity\Exploitation;
use App\Entity\Ilots;
use App\Entity\IndexCultures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
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

    /**
     * @param $ilot
     * @return mixed
     */
    public function countAvailableSizeCulture( $ilot )
    {
        //TODO: Catch ?
        try {
            $totalSize = $this->createQueryBuilder('t')
                ->select('SUM(t.size)')
                ->andWhere('t.ilot = :ilot')
                ->setParameter('ilot', $ilot)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }

        $ilotSize = $ilot->getSize();

        return $ilotSize - $totalSize;
    }

    /**
     * @param IndexCultures $culture
     * @param Exploitation $exploitation
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countSizeByIndexCulture(IndexCultures $culture, Exploitation $exploitation )
    {
        //TODO: Catch ?
        return $this->createQueryBuilder('c')
            ->select('SUM(c.size)')
            ->where('c.name = :name')
            ->setParameter('name', $culture)
            ->leftJoin(Ilots::class, 'i', 'WITH', 'i.id = c.ilot')
            ->andWhere( 'i.exploitation = :exploitation' )
            ->setParameter( 'exploitation', $exploitation )
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findCultureByExploitation( $exploitation )
    {
        return $this->createQueryBuilder('c')
            ->leftJoin(Ilots::class, 'i', 'WITH', 'i.id = c.ilot')
            ->where('i.exploitation = :exploitation')
            ->setParameter('exploitation', $exploitation)
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
