<?php

namespace App\Repository;

use App\Entity\Cultures;
use App\Entity\Ilots;
use App\Entity\IndexCultures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method IndexCultures|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexCultures|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexCultures[]    findAll()
 * @method IndexCultures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexCulturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndexCultures::class);
    }

    public function findCulturesByExploitation( $exploitation, $result = NULL )
    {
        $query = $this->createQueryBuilder('q')
            ->leftJoin( Cultures::class, 'c', 'WITH', 'q.id = c.name')
            ->leftJoin(Ilots::class, 'i', 'WITH', 'c.ilot = i.id')
            ->andWhere('i.exploitation = :exp')
            ->setParameter('exp', $exploitation)
            ->orderBy('c.name', 'DESC')
        ;

        //-- Only Query
        if ($result) {
            return $query;
        }

        //-- Return Array
        return $query->getQuery()
            ->getResult();
    }

    // /**
    //  * @return IndexCultures[] Returns an array of IndexCultures objects
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
    public function findOneBySomeField($value): ?IndexCultures
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
