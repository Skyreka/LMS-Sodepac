<?php

namespace App\Repository;

use App\Entity\Panoramas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Panoramas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panoramas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panoramas[]    findAll()
 * @method Panoramas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanoramasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panoramas::class);
    }

    /**
     * @param $idTechnician
     * @return mixed
     */
    public function findAllPanoramasOfTechnician( $idTechnician )
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.technician = :technician')
            ->setParameter('technician', $idTechnician)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Panoramas[] Returns an array of Panoramas objects
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
    public function findOneBySomeField($value): ?Panoramas
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