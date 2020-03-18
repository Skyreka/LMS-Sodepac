<?php

namespace App\Repository;

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