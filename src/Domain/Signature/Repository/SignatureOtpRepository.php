<?php

namespace App\Domain\Signature\Repository;

use App\Domain\Signature\Entity\Signature;
use App\Domain\Signature\Entity\SignatureOtp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SignatureOtp|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignatureOtp|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignatureOtp[]    findAll()
 * @method SignatureOtp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignatureOtpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SignatureOtp::class);
    }
    
    /**
     * @param Signature $signature
     * @param $code
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findValidCodeBySignature(Signature $signature, $code)
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('s')
            ->andWhere('s.signature = :signature')
            ->andWhere('s.expiredAt >= :now')
            ->andWhere('s.code = :code')
            ->andWhere('s.isActive = 1')
            ->setParameter('signature', $signature)
            ->setParameter('code', $code)
            ->setParameter('now', $now)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    // /**
    //  * @return SignatureOtp[] Returns an array of SignatureOtp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    /*
    public function findOneBySomeField($value): ?SignatureOtp
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
