<?php

namespace App\Domain\Product\Repository;

use App\Domain\Product\Entity\RiskPhase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RiskPhase|null find($id, $lockMode = null, $lockVersion = null)
 * @method RiskPhase|null findOneBy(array $criteria, array $orderBy = null)
 * @method RiskPhase[]    findAll()
 * @method RiskPhase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RiskPhaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RiskPhase::class);
    }
    
    // /**
    //  * @return RiskPhase[] Returns an array of RiskPhase objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    /*
    public function findOneBySomeField($value): ?RiskPhase
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
