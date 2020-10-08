<?php

namespace App\Repository;

use App\Entity\Trade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trade|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trade|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trade[]    findAll()
 * @method Trade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trade::class);
    }

    /**
    * @return Trade[] Returns an array of Trade objects
    */

    public function findBankRecharge($value)
    {
        return $this->createQueryBuilder('t')
            ->select("s.msisdn as sim,COUNT(t.id) as total, SUM(t.amount) as amount, CONCAT(MONTHNAME(t.transactionAt), ' ', YEAR(t.transactionAt)) as day")
            ->innerJoin('t.toMsisdn', 's')
            ->andWhere('s.msisdn = :val')
            ->andWhere('t.fromMsisdn is null')
            ->setParameter('val', $value)
            ->groupBy('day')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findGiveReceivedByPos($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.pointofsale', 'pos')
            ->andWhere('pos.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveSendByPos($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.pointofsale', 'pos')
            ->andWhere('pos.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveReceivedByTrader($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveSendByTrader($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findLastDate()
    {
        return $this->createQueryBuilder('t')
            ->select('DATE(t.transactionAt) as day')
            ->orderBy('day', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Trade
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
