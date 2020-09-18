<?php

namespace App\Repository;

use App\Entity\Exploitation;
use App\Entity\Recommendations;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
            ->andWhere('r.status = :status')
            ->setParameter('tech', $technician )
            ->setParameter('status', '2')
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
    public function findByExploitationOfTechnicianAndYear( $technician, $year, $limit = null)
    {
        $query = $this->createQueryBuilder('r')
            ->leftJoin( Exploitation::class,'e','WITH', 'r.exploitation = e.id' )
            ->leftJoin( Users::class, 'u', 'WITH', 'e.users = u.id')
            ->where('u.technician = :tech')
            ->andWhere('year(r.create_at) = :year')
            ->andWhere('r.status = :status')
            ->setParameter('tech', $technician )
            ->setParameter('year', $year)
            ->setParameter('status', '2')
            ->orderBy('r.create_at', 'DESC')
            ;

        if ($limit != NULL) {
            $query = $query->setMaxResults( $limit );
        }

        return $query->getQuery()->getResult();
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
            ->andWhere('r.status = :status')
            ->setParameter('status', '2' )
            ->setParameter('customer', $customer )
            ->setParameter('year', $year)
            ->orderBy('r.create_at', 'DESC')
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
            ->andWhere( 'r.status = :status' )
            ->setParameter('status', '2')
            ->orderBy('r.create_at', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
