<?php

namespace App\Repository;

use App\Entity\MonthlyReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MonthlyReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method MonthlyReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method MonthlyReport[]    findAll()
 * @method MonthlyReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonthlyReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonthlyReport::class);
    }

    // /**
    //  * @return MonthlyReport[] Returns an array of MonthlyReport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MonthlyReport
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
