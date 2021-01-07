<?php

namespace App\Repository;

use App\Entity\Doses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function findByProduct( $product, $return = null )
    {
        $query = $this->createQueryBuilder('s')
            ->andWhere('s.product = :product')
            ->setParameter('product', $product)
            ->orderBy('s.id', 'ASC')
        ;

        if ($return) {
            $query = $query->getQuery()
                ->getResult();
        }

        return $query;
    }

    public function findDose( $product, $indexCulture, $return = null )
    {
        $query = $this->createQueryBuilder('s')
            ->andWhere('s.product = :product')
            ->andWhere('s.indexCulture = :indexCulture')
            ->setParameter('product', $product)
            ->setParameter('indexCulture', $indexCulture)
            ->orderBy('s.id', 'ASC')
        ;

        // Default value for select
        if ( empty($query->getQuery()->getResult()) ) {
            $query = $this->createQueryBuilder( 'd' )
                ->where('d.id = 0');
        }

        if ($return) {
            $query = $query->getQuery()
                ->getResult();
        }

        return $query;
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
