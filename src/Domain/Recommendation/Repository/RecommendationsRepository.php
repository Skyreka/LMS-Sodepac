<?php

namespace App\Domain\Recommendation\Repository;

use App\Domain\Auth\Users;
use App\Domain\Exploitation\Entity\Exploitation;
use App\Domain\Recommendation\Entity\Recommendations;
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
     * @param $status
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllByStatus($status)
    {
        return $this->createQueryBuilder('r')
            ->where('r.status = :status')
            ->setParameter('status', $status)
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    /**
     * @param $technician
     * @return mixed
     */
    public function findByExploitationOfTechnician($technician, $limit = null)
    {
        $query = $this->createQueryBuilder('r')
            ->leftJoin(Exploitation::class, 'e', 'WITH', 'r.exploitation = e.id')
            ->leftJoin(Users::class, 'u', 'WITH', 'e.users = u.id')
            ->where('u.technician = :tech')
            ->andWhere('r.status = :status')
            ->setParameter('tech', $technician)
            ->setParameter('status', '2')
            ->orderBy('r.create_at', 'DESC');
        
        if($limit != NULL) {
            $query = $query->setMaxResults($limit);
        }
        
        return $query->getQuery()->getResult();
    }
    
    /**
     * @param $technician
     * @param $year
     * @return mixed
     */
    public function findByExploitationOfTechnicianAndYear($technician, $year, $limit = null)
    {
        $query = $this->createQueryBuilder('r')
            ->leftJoin(Exploitation::class, 'e', 'WITH', 'r.exploitation = e.id')
            ->leftJoin(Users::class, 'u', 'WITH', 'e.users = u.id')
            ->where('u.technician = :tech')
            ->andWhere('year(r.create_at) = :year')
            ->andWhere('r.status >= 1')
            ->setParameter('tech', $technician)
            ->setParameter('year', $year)
            ->orderBy('r.create_at', 'DESC');
        
        if($limit != NULL) {
            $query = $query->setMaxResults($limit);
        }
        
        return $query->getQuery()->getResult();
    }
    
    /**
     * @param $customer
     * @param $year
     * @return mixed
     */
    public function findByExploitationOfCustomerAndYear($customer, $year)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin(Exploitation::class, 'e', 'WITH', 'r.exploitation = e.id')
            ->where('e.users = :customer')
            ->andWhere('year(r.create_at) = :year')
            ->andWhere('r.status = :status')
            ->setParameter('status', '3')
            ->setParameter('customer', $customer)
            ->setParameter('year', $year)
            ->orderBy('r.create_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * @param $customer
     * @param $year
     * @return mixed
     */
    public function findByExploitationOfCustomerAndYearAndNotChecked($customer, $year)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin(Exploitation::class, 'e', 'WITH', 'r.exploitation = e.id')
            ->where('e.users = :customer')
            ->andWhere('year(r.create_at) = :year')
            ->andWhere('r.status = :status')
            ->andWhere('r.checked = 0')
            ->setParameter('status', '3')
            ->setParameter('customer', $customer)
            ->setParameter('year', $year)
            ->orderBy('r.create_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * @param $year
     * @param null $limit
     * @return mixed
     */
    public function findAllByYear($year, $limit = null)
    {
        
        $query = $this->createQueryBuilder('r')
            ->where('year(r.create_at) = :year')
            ->andWhere('r.status >= 1')
            ->setParameter('year', $year)
            ->orderBy('r.create_at', 'DESC');
        
        if($limit != NULL) {
            $query->setMaxResults($limit);
        }
        
        return $query->getQuery()->getResult();
    }
}