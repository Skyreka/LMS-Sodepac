<?php

namespace App\Repository;

use App\Entity\BsvUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BsvUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method BsvUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method BsvUsers[]    findAll()
 * @method BsvUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BsvUsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BsvUsers::class);
    }

    /**
     * @param $year
     * @param $customer
     * @return mixed
     */
    public function findAllByYearAndCustomer($year, $customer)
    {

        return $this->createQueryBuilder('b')
            ->where('year(b.display_at) = :year')
            ->andWhere('b.customers = :customer')
            ->setParameter('year', $year)
            ->setParameter('customer', $customer)
            ->orderBy('b.display_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $year
     * @return mixed
     */
    public function findAllByYear($year)
    {

        return $this->createQueryBuilder('b')
            ->where('year(b.display_at) = :year')
            ->setParameter('year', $year)
            ->orderBy('b.display_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return BsvUsers[] Returns an array of BsvUsers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BsvUsers
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
