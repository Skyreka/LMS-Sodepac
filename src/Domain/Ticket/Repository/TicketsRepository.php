<?php

namespace App\Domain\Ticket\Repository;

use App\Domain\Ticket\Entity\Tickets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tickets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tickets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tickets[]    findAll()
 * @method Tickets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tickets::class);
    }
    
    public function findAllByUser($user, $limit = null)
    {
        $req = $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->orderBy('t.id', 'DESC');
        
        if(false === is_null($limit)) {
            $req->setMaxResults($limit);
        }
        
        return $req->getQuery()->getResult();
    }
    
    public function findAllByTechnician($technician, $limit = null)
    {
        $req = $this->createQueryBuilder('t')
            ->where('t.technician = :technician')
            ->setParameter('technician', $technician)
            ->orderBy('t.id', 'DESC');
        
        if(false === is_null($limit)) {
            $req->setMaxResults($limit);
        }
        
        return $req->getQuery()->getResult();
    }
    
    // /**
    //  * @return Tickets[] Returns an array of Tickets objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    /*
    public function findOneBySomeField($value): ?Tickets
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
