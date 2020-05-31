<?php

namespace App\Repository;

use App\Entity\Products;
use App\Entity\Stocks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Stocks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stocks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stocks[]    findAll()
 * @method Stocks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StocksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stocks::class);
    }

    /**
     * Return Stock Products by exploitation
     * @param $exp
     * @param null $return
     * @return QueryBuilder
     */
    public function findByExploitation( $exp, $return = null )
    {
        $query = $this->createQueryBuilder('s')
            ->andWhere('s.exploitation = :exp')
            ->setParameter('exp', $exp)
            ->orderBy('s.id', 'ASC')
        ;

        if ($return) {
            $query = $query->getQuery()
                ->getResult();
        }

        return $query;
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

    /**
     * Function return stock & product by exploitation and only product on fumure category
     * @param $exp
     * @param null $return
     * @return QueryBuilder
     */
    public function findProductInStockByExploitation( $exp, $return = null )
    {
        $query = $this->createQueryBuilder('s')
            ->leftJoin(Products::class, 'p', 'WITH', 'p.id = s.product')
            ->andWhere('s.exploitation = :exp')
            ->andWhere('s.quantity > 0')
            ->setParameter('exp', $exp)
            ->orderBy('s.id', 'ASC')
        ;

        if ($return) {
            $query = $query->getQuery()
                ->getResult();
        }

        return $query;
    }



    /*
    public function findOneBySomeField($value): ?Stocks
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
