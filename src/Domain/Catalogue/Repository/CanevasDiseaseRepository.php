<?php

namespace App\Domain\Catalogue\Repository;

use App\Domain\Catalogue\Entity\CanevasDisease;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CanevasDisease>
 *
 * @method CanevasDisease|null find($id, $lockMode = null, $lockVersion = null)
 * @method CanevasDisease|null findOneBy(array $criteria, array $orderBy = null)
 * @method CanevasDisease[]    findAll()
 * @method CanevasDisease[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CanevasDiseaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CanevasDisease::class);
    }

    public function add(CanevasDisease $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CanevasDisease $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CanevasDisease[] Returns an array of CanevasDisease objects
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

//    public function findOneBySomeField($value): ?CanevasDisease
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
