<?php

namespace App\Domain\Intervention\Repository;

use App\Entity\InterventionsProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InterventionsProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterventionsProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterventionsProducts[]    findAll()
 * @method InterventionsProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionsProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InterventionsProducts::class);
    }
    
    // /**
    //  * @return InterventionsProducts[] Returns an array of InterventionsProducts objects
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
    public function findOneBySomeField($value): ?InterventionsProducts
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
