<?php

namespace App\Repository;

use App\Entity\PanoramaUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PanoramaUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method PanoramaUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method PanoramaUser[]    findAll()
 * @method PanoramaUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanoramaUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PanoramaUser::class);
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
