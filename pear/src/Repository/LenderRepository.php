<?php

namespace App\Repository;

use App\Entity\Lender;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lender|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lender|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lender[]    findAll()
 * @method Lender[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LenderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lender::class);
    }

    // /**
    //  * @return Lender[] Returns an array of Lender objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lender
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
