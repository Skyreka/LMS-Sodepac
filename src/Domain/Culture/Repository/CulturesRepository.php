<?php

namespace App\Domain\Culture\Repository;

use App\Domain\Culture\Entity\Cultures;
use App\Domain\Exploitation\Entity\Exploitation;
use App\Domain\Ilot\Entity\Ilots;
use App\Domain\Index\Entity\IndexCultures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cultures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cultures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cultures[]    findAll()
 * @method Cultures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CulturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cultures::class);
    }
    
    /**
     * @param $ilot
     * @return mixed
     */
    public function countAvailableSizeCulture($ilot)
    {
        //TODO: Catch ?
        try {
            $totalSize = $this->createQueryBuilder('t')
                ->select('SUM(t.size)')
                ->where('t.ilot = :ilot')
                ->andWhere('t.status = :status')
                ->setParameter('ilot', $ilot)
                ->setParameter('status', 0)
                ->getQuery()
                ->getSingleScalarResult();
        } catch(NoResultException $e) {
        } catch(NonUniqueResultException $e) {
        }
        
        $ilotSize = $ilot->getSize();
        
        return $ilotSize - $totalSize;
    }
    
    /**
     * @param IndexCultures $culture
     * @param Exploitation $exploitation
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countSizeByIndexCulture(IndexCultures $culture, Exploitation $exploitation)
    {
        //TODO: Catch ?
        return $this->createQueryBuilder('c')
            ->select('SUM(c.size)')
            ->where('c.name = :name')
            ->setParameter('name', $culture)
            ->leftJoin(Ilots::class, 'i', 'WITH', 'i.id = c.ilot')
            ->andWhere('i.exploitation = :exploitation')
            ->setParameter('exploitation', $exploitation)
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function findCultureByExploitation($exploitation)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin(Ilots::class, 'i', 'WITH', 'i.id = c.ilot')
            ->where('i.exploitation = :exploitation')
            ->setParameter('exploitation', $exploitation);
    }
    
    /**
     * Find All Culture By ilot
     */
    public function findByIlot($ilot, $year = null, $return = null)
    {
        if( NULL === $year ) {
            $year = new \DateTime('Y');
        }
        $query = $this->createQueryBuilder('c')
            ->where('c.ilot = :ilot')
            ->andWhere('YEAR(c.addedAt) = :year')
            ->setParameter('ilot', $ilot)
            ->setParameter('year', $year)
            ->orderBy('c.name', 'DESC');
        
        if($return) {
            return $query;
        }
        
        return $query
            ->getQuery()
            ->getResult();
    }
    
    public function findByIlotCultureInProgress($ilot, $culture)
    {
        return $this->createQueryBuilder('c')
            ->where('c.ilot = :ilot')
            ->andWhere('c.status != :status')
            ->andWhere('c.name = :name')
            ->setParameter('status', 1)
            ->setParameter('ilot', $ilot)
            ->setParameter('name', $culture)
            ->getQuery()
            ->getResult();
    }
    
    /**
     * @param $indexNameId
     * @param $exploitation
     * @param null $onlyQuery
     * @return \Doctrine\ORM\QueryBuilder|mixed
     */
    public function findByIndexCultureInProgress($indexNameId, $exploitation, $onlyQuery = false)
    {
        $query = $this->createQueryBuilder('c')
            ->leftJoin(Ilots::class, 'il', 'WITH', 'il.id = c.ilot')
            ->where('c.name = :nameId')
            ->andWhere('il.exploitation = :exploitation')
            ->andWhere('c.status = :status')
            ->setParameter('nameId', $indexNameId)
            ->setParameter('status', 0)
            ->setParameter('exploitation', $exploitation);
        
        if($onlyQuery) {
            return $query;
        }
        
        return $query->getQuery()->getResult();
    }
    
    // /**
    //  * @return Cultures[] Returns an array of Cultures objects
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
    public function findOneBySomeField($value): ?Cultures
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
