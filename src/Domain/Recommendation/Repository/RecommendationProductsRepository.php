<?php

namespace App\Domain\Recommendation\Repository;

use App\Domain\Product\Entity\Products;
use App\Domain\Recommendation\Entity\RecommendationProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RecommendationProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecommendationProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecommendationProducts[]    findAll()
 * @method RecommendationProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecommendationProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecommendationProducts::class);
    }
    
    public function productExist($recommendation, $slugProduct, $dose = NULL)
    {
        $product = $this->getEntityManager()->getRepository(Products::class);
        $product = $product->findProductBySlug($slugProduct);
        
        if($dose) {
            return $this->createQueryBuilder('r')
                ->where('r.product = :product')
                ->andWhere('r.recommendation = :recommendation')
                ->andWhere('r.dose = :dose')
                ->setParameter('dose', $dose)
                ->setParameter('product', $product)
                ->setParameter('recommendation', $recommendation)
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('r')
                ->where('r.product = :product')
                ->andWhere('r.recommendation = :recommendation')
                ->setParameter('product', $product)
                ->setParameter('recommendation', $recommendation)
                ->getQuery()
                ->getResult();
        }
    }
    
    /**
     * Get value of quantity used from unit
     * @param $unit
     * @param $unit2
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function FindQuantityUsedByUnit($unit, $unit2)
    {
        return $this->createQueryBuilder('r')
            ->where('r.unit = :unit')
            ->orWhere('r.unit = :unit2')
            ->setParameter('unit', $unit)
            ->setParameter('unit2', $unit2)
            ->select('SUM(r.quantity)')
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    /**
     * Get array if c_id
     * @param $recommendation
     * @return array
     */
    public function findCId($recommendation)
    {
        $result = [];
        
        $req = $this->createQueryBuilder('r')
            ->where('r.recommendation = :recommendation')
            ->setParameter('recommendation', $recommendation)
            ->select('r.c_id')
            ->getQuery()
            ->getResult();;
        
        foreach($req as $key) {
            array_push($result, $key['c_id']);
        }
        
        return $result;
    }
    
    // /**
    //  * @return RecommendationProducts[] Returns an array of RecommendationProducts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    /*
    public function findOneBySomeField($value): ?RecommendationProducts
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
