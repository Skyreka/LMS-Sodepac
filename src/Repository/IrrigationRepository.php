<?php

namespace App\Repository;

use App\Entity\Ilots;
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
    * @return Irrigation[] Returns an array of Irrigation objects
    */
    public function findByUser()
    {
        $result = $this->createQueryBuilder('od')
            ->join('od.order', 'o')
            ->addSelect('o')
            ->where('o.userid = :userid')
            ->andWhere('od.orderstatusid IN (:orderstatusid)')
            ->setParameter('userid', $userid)
            ->setParameter('orderstatusid', array(5, 6, 7, 8, 10))
            ->getQuery()->getResult()
        ;
        return $result;
    }

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
