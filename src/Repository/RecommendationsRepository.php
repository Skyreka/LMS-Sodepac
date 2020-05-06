<?php

namespace App\Repository;

use App\Entity\Exploitation;
use App\Entity\Recommendations;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Recommendations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recommendations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recommendations[]    findAll()
 * @method Recommendations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecommendationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recommendations::class);
    }

    /**
     * @param $technician
     * @return mixed
     */
    public function findByExploitationOfTechnician( $technician )
    {
        return $this->createQueryBuilder('r')
            ->leftJoin( Exploitation::class,'e','WITH', 'r.exploitation = e.id' )
            ->leftJoin( Users::class, 'u', 'WITH', 'e.users = u.id')
            ->where('u.technician = :tech')
            ->setParameter('tech', $technician )
            ->orderBy('r.create_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $technician
     * @param $year
     * @return mixed
     */
    public function findByExploitationOfTechnicianAndYear( $technician, $year )
    {
        return $this->createQueryBuilder('r')
            ->leftJoin( Exploitation::class,'e','WITH', 'r.exploitation = e.id' )
            ->leftJoin( Users::class, 'u', 'WITH', 'e.users = u.id')
            ->where('u.technician = :tech')
            ->andWhere('year(r.create_at) = :year')
            ->setParameter('tech', $technician )
            ->setParameter('year', $year)
            ->orderBy('r.create_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $customer
     * @param $year
     * @return mixed
     */
    public function findByExploitationOfCustomerAndYear( $customer, $year )
    {
        return $this->createQueryBuilder('r')
            ->leftJoin( Exploitation::class,'e','WITH', 'r.exploitation = e.id' )
            ->where('e.users = :customer')
            ->andWhere('year(r.create_at) = :year')
            ->setParameter('customer', $customer )
            ->setParameter('year', $year)
            ->orderBy('r.create_at', 'ASC')
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

        return $this->createQueryBuilder('r')
            ->where('year(r.create_at) = :year')
            ->setParameter('year', $year)
            ->andWhere( 'r.status = :status1' )
            ->orWhere( 'r.status = :status2' )
            ->setParameter( 'status1',  '1' )
            ->setParameter( 'status2',  '2' )
            ->orderBy('r.create_at', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Recommendations[] Returns an array of Recommendations objects
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
    public function findOneBySomeField($value): ?Recommendations
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
