<?php

namespace App\Domain\Panorama\Repository;

use App\Domain\Panorama\Entity\PanoramaSend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PanoramaSend|null find($id, $lockMode = null, $lockVersion = null)
 * @method PanoramaSend|null findOneBy(array $criteria, array $orderBy = null)
 * @method PanoramaSend[]    findAll()
 * @method PanoramaSend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanoramaSendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PanoramaSend::class);
    }


    /**
     * @param $customer
     * @param null $limit
     * @return mixed
     * @throws \Exception
     */
    public function findAllByCustomer($customer, $limit = null)
    {
        $req = $this->createQueryBuilder('b')
            ->andWhere('b.customers = :customer')
            ->andWhere('b.checked = 0')
            ->andWhere('b.display_at < :now')
            ->setParameter('customer', $customer)
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('b.display_at', 'DESC');

        if(false === is_null($limit)) {
            $req->setMaxResults($limit);
        }

        return $req->getQuery()->getResult();
    }

    public function findAllByYearAndCustomer($customer)
    {

        return $this->createQueryBuilder('b')
            ->andWhere('b.display_at <= :startDate')
            ->andWhere('b.display_at >= :endDate')
            ->andWhere('b.customers = :customer')
            ->setParameter('customer', $customer)
            ->setParameter('startDate', new \DateTime('now'))
            ->setParameter('endDate', new \DateTime('-90 days'))
            ->orderBy('b.display_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $year
     * @param $sender
     * @return mixed
     */
    public function findAllByYearAndSender($year, $sender)
    {
        return $this->createQueryBuilder('b')
            ->where('year(b.display_at) = :year')
            ->andWhere('b.sender = :sender')
            ->setParameter('year', $year)
            ->setParameter('sender', $sender)
            ->orderBy('b.display_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $year
     * @return mixed
     */
    public function findAllByYear($year)
    {

        return $this->createQueryBuilder('p')
            ->where('year(p.display_at) = :year')
            ->setParameter('year', $year)
            ->orderBy('p.display_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return PanoramaUser[] Returns an array of PanoramaUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PanoramaUser
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
