<?php

namespace App\Repository;

use App\Entity\Analyse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Analyse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Analyse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Analyse[]    findAll()
 * @method Analyse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnalyseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Analyse::class);
    }

    /**
     * @param $user
     * @param null $limit
     * @return mixed
     */
    public function findByUser( $user, $limit = null )
    {
        $query = $this->createQueryBuilder('i')
            ->andWhere('i.exploitation = :exp')
            ->setParameter('exp', $user->getExploitation())
            ->orderBy('i.id', 'ASC')
        ;

        if ($limit != NULL) {
            $query = $query->setMaxResults( $limit );
        }

        return $query->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Analyse[] Returns an array of Analyse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Analyse
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
