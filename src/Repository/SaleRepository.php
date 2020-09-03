<?php

namespace App\Repository;

use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sale[]    findAll()
 * @method Sale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    public function findSaleByDate()
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, CONCAT(MONTHNAME(s.transactionAt), ' ', YEAR(s.transactionAt)) as day, t.title")
            ->innerJoin('s.type', 't')
            ->where('t.title = :val1')
            ->orwhere('t.title = :val2')
            ->setParameters(['val1'=>'AGNT', 'val2' =>'CSIN'])
            ->groupBy('day, s.type')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findDistinctType()
    {
        return $this->createQueryBuilder('s')
            ->select('DISTINCT(t.title) as type')
            ->innerJoin('s.type', 't')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findDistinctDate()
    {
        return $this->createQueryBuilder('s')
            ->select("SUM(s.dComm) as dComm, CONCAT(MONTHNAME(s.transactionAt), ' ', YEAR(s.transactionAt)) as day")
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Sale[] Returns an array of Sale objects
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
    public function findOneBySomeField($value): ?Sale
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
