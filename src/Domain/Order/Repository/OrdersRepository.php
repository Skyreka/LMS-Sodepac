<?php

namespace App\Domain\Order\Repository;


use App\Domain\Auth\Users;
use App\Domain\Order\Entity\Orders;
use App\Repository\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }
    
    public function findByTechnician(Users $tech, $limit = null)
    {
        $req = $this->createQueryBuilder('o')
            ->andWhere('o.creator = :val')
            ->andWhere('o.status != 0')
            ->setParameter('val', $tech)
            ->orderBy('o.addedAt', 'DESC');
        
        if($limit) {
            $req->setMaxResults($limit);
        }
        
        return $req->getQuery()->getResult();
    }
    
    /**
     * @param $user
     * @return int|mixed|string
     */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('o')
            ->where('o.customer = :user')
            ->andWhere('o.status != 0')
            ->setParameter('user', $user)
            ->orderBy('o.addedAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    
    /**
     * @return int|mixed|string
     */
    public function findByAdmin()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.addedAt', 'DESC')
            ->andWhere('o.status != 0')
            ->getQuery()
            ->getResult();
    }
    
    // /**
    //  * @return Cart[] Returns an array of Cart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    /*
    public function findOneBySomeField($value): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}