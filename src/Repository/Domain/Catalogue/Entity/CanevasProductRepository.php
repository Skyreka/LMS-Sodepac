<?php

namespace App\Repository\Domain\Catalogue\Entity;

use App\Domain\Catalogue\Entity\CanevasProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CanevasProduct>
 *
 * @method CanevasProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method CanevasProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method CanevasProduct[]    findAll()
 * @method CanevasProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CanevasProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CanevasProduct::class);
    }

    public function add(CanevasProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CanevasProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CanevasProduct[] Returns an array of CanevasProduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CanevasProduct
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
