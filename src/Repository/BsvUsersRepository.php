<?php

namespace App\Repository;

use App\Entity\BsvUsers;
use App\Entity\Users;
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
     * Find all bsv relation from customer
     * @param $customer
     * @param null $limit
     * @return mixed
     */
    public function findAllByCustomer($customer, $limit = null)
    {

        $req = $this->createQueryBuilder('b')
            ->andWhere('b.customers = :customer')
            ->andWhere('b.checked = 0')
            ->andWhere('b.display_at < :now')
            ->setParameter('customer', $customer)
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('b.display_at', 'ASC')
            ;

        if (false === is_null($limit)) {
            $req->setMaxResults( $limit );
        }

        return $req->getQuery()->getResult();
    }

    /**
     * Find all bsv relations from technician and year
     * @param $year
     * @param $customer
     * @return mixed
     */
    public function findAllByYearAndCustomer($year, $customer)
    {

        return $this->createQueryBuilder('b')
            ->where('year(b.display_at) = :year')
            ->andWhere('b.customers = :customer')
            ->andWhere('b.display_at < :now')
            ->setParameter('year', $year)
            ->setParameter('customer', $customer)
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('b.display_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Find all bsv relations from year
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

    /**
     * Find all bsv relations from technician
     * @param $technician
     * @param null $limit
     * @return mixed
     */
    public function findAllByTechnician($technician, $limit = null)
    {
        $req = $this->createQueryBuilder('b')
            ->leftJoin(Users::class, 'u', 'WITH', 'u.id = b.customers')
            ->leftJoin(Users::class, 't', 'WITH', 't.id = u.technician')
            ->where('t.id = :technician')
            ->setParameter('technician', $technician)
            ;

        if (false === is_null($limit)) {
            $req->setMaxResults( $limit );
        }

        return $req->getQuery()->getResult();
    }

    /**
     * Find all bsv relations from technician
     * @param $year
     * @param $technician
     * @param null $limit
     * @return mixed
     */
    public function findAllByYearAndTechnician($year, $technician, $limit = null)
    {
        $req = $this->createQueryBuilder('b')
            ->leftJoin(Users::class, 'u', 'WITH', 'u.id = b.customers')
            ->leftJoin(Users::class, 't', 'WITH', 't.id = u.technician')
            ->where('t.id = :technician')
            ->andWhere('year(b.display_at) = :year')
            ->setParameter('technician', $technician)
            ->setParameter('year', $year)
        ;

        if (false === is_null($limit)) {
            $req->setMaxResults( $limit );
        }

        return $req->getQuery()->getResult();
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
