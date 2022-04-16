<?php

namespace App\Domain\Index\Repository;

use App\Domain\Culture\Entity\Cultures;
use App\Domain\Ilot\Entity\Ilots;
use App\Domain\Index\Entity\IndexCultures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IndexCultures|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexCultures|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexCultures[]    findAll()
 * @method IndexCultures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexCulturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndexCultures::class);
    }
    
    /**
     * Find all index cultures is splay 1
     */
    public function findDisplay()
    {
        return $this->createQueryBuilder('q')
            ->where('q.isDisplay = 1')
            ->orderBy('q.name', 'ASC');
    }
    
    public function findCulturesByExploitation($exploitation, $result = NULL)
    {
        $query = $this->createQueryBuilder('q')
            ->leftJoin(Cultures::class, 'c', 'WITH', 'q.id = c.name')
            ->leftJoin(Ilots::class, 'i', 'WITH', 'c.ilot = i.id')
            ->andWhere('i.exploitation = :exp')
            ->setParameter('exp', $exploitation)
            ->orderBy('c.name', 'DESC');
        
        //-- Only Query
        if($result) {
            return $query;
        }
        
        //-- Return Array
        return $query->getQuery()
            ->getResult();
    }
    
    public function findAllAlpha($result = NULL)
    {
        $query = $this->createQueryBuilder('i')
            ->orderBy('i.name', 'ASC');
        
        //-- Only Query
        if($result) {
            return $query;
        }
        
        //-- Return Array
        return $query->getQuery()
            ->getResult();
    }
    
    // /**
    //  * @return IndexCultures[] Returns an array of IndexCultures objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    /*
    public function findOneBySomeField($value): ?IndexCultures
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
