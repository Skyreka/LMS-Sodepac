<?php

namespace App\Domain\Catalogue\Repository;

use App\Domain\Catalogue\Entity\CanevasStep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CanevasStep>
 *
 * @method CanevasStep|null find($id, $lockMode = null, $lockVersion = null)
 * @method CanevasStep|null findOneBy(array $criteria, array $orderBy = null)
 * @method CanevasStep[]    findAll()
 * @method CanevasStep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CanevasStepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CanevasStep::class);
    }

    public function add(CanevasStep $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CanevasStep $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CanevasStep[] Returns an array of CanevasStep objects
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

//    public function findOneBySomeField($value): ?CanevasStep
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
