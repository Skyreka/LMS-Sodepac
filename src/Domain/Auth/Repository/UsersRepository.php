<?php

namespace App\Domain\Auth\Repository;

use App\Domain\Auth\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    /**
     * UsersRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }
    
    /**
     * @param $idTechnician
     * @param null $limit
     * @return mixed
     */
    public function findAllCustomersOfTechnician($idTechnician, $limit = NULL)
    {
        $req = $this->createQueryBuilder('u')
            ->andWhere('u.technician = :technician')
            ->andWhere('u.status = :status')
            ->setParameter('technician', $idTechnician)
            ->setParameter('status', 'ROLE_USER')
            ->orderBy('u.id', 'ASC');
        
        if(false === is_null($limit)) {
            $req->setMaxResults($limit);
        }
        
        return $req->getQuery()->getResult();
    }
    
    /**
     * @param $role
     * @param null $limit
     * @return mixed
     */
    public function findAllByRole($role, $limit = NULL)
    {
        $req = $this->createQueryBuilder('u')
            ->andWhere('u.status = :role')
            ->setParameter('role', $role)
            ->orderBy('u.id', 'ASC');
        
        if(false === is_null($limit)) {
            $req->setMaxResults($limit);
        }
        
        return $req->getQuery()->getResult();
    }
    
    /**
     * @param $role
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllByRole($role)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.status = :role')
            ->setParameter('role', $role)
            ->orderBy('u.id', 'ASC')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    /**
     * @param null $limit
     * @return mixed
     */
    public function findAllUsersAndTechnician($limit = NULL)
    {
        $req = $this->createQueryBuilder('u')
            ->Where('u.status = :user')
            ->orWhere('u.status = :tech')
            ->setParameter('user', 'ROLE_USER')
            ->setParameter('tech', 'ROLE_TECHNICIAN')
            ->orderBy('u.id', 'ASC');
        
        if(false === is_null($limit)) {
            $req->setMaxResults($limit);
        }
        
        return $req->getQuery()->getResult();
    }
    
    /**
     * @param $pack
     * @param null $limit
     * @return mixed
     */
    public function findAllByPack($pack, $limit = NULL)
    {
        $req = $this->createQueryBuilder('u')
            ->andWhere('u.pack = :pack')
            ->setParameter('pack', $pack)
            ->orderBy('u.id', 'ASC');
        
        if(false === is_null($limit)) {
            $req->setMaxResults($limit);
        }
        
        return $req->getQuery()->getResult();
    }
    
    /**
     * @return mixed
     */
    public function findAllPanorama()
    {
        $req = $this->createQueryBuilder('u')
            ->andWhere('u.pack = :full')
            ->setParameter('full', 'PACK_FULL')
            ->orWhere('u.pack = :light')
            ->setParameter('light', 'PACK_LIGHT')
            ->andWhere('u.email IS NOT NULL')
            ->orderBy('u.id', 'ASC');
        
        return $req->getQuery()->getResult();
    }
    
    /**
     * @param $pack
     * @param null $limit
     * @return mixed
     */
    public function countAllByPack($pack)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.pack = :pack')
            ->setParameter('pack', $pack)
            ->orderBy('u.id', 'ASC')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    // /**
    //  * @return Users[] Returns an array of Users objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}