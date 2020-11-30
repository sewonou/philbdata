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
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['val' => $value, 'bool'=> true ])
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
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false])
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
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false])
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
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findVirtualToBankByTrader($date1, $date2, $id)
    {
    return $this->createQueryBuilder('t')
        ->innerJoin('t.type', 'type')
        ->innerJoin('t.fromMsisdn', 's')
        ->innerJoin('s.trader', 'trader')
        ->andWhere('trader.id = :id')
        ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
        ->andWhere('type.title = :val' )
        ->andwhere('t.toMsisdn is null')
        ->andWhere('t.isBankGive = :bool')
        ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>true])
        ->getQuery()
        ->getResult()
        ;
    }

    public function findVirtualToBankByTraderTotal($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.toMsisdn is null')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>true])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findVirtualToBankByPointofsaleTotal($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.pointofsale', 'pointofsale')
            ->andWhere('pointofsale.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.toMsisdn is null')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>true])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findVirtualFromBankToTrader($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.fromMsisdn is null')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>true])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findVirtualFromBankToTraderTotal($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.fromMsisdn is null')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>true])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findVirtualFromBankToPointofsaleTotal($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.pointofsale', 'pointofsale')
            ->andWhere('pointofsale.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.fromMsisdn is null')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>true])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findVirtualToPosByTrader($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.toMsisdn is not null')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findVirtualToPosByTraderTotal($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.toMsisdn is not null')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findVirtualToOtherByPosTotal($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.pointofsale', 'pointofsale')
            ->andWhere('pointofsale.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.toMsisdn is not null')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findVirtualFromPosToTrader($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.fromMsisdn is  not null')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findVirtualFromPosToTraderTotal($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.fromMsisdn is  not null')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findVirtualFromPosToOtherTotal($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.pointofsale', 'pointofsale')
            ->andWhere('pointofsale.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.fromMsisdn is  not null')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findVirtualToMasterByTrader($date1, $date2, $id, $master)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.toMsisdn = :master')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'master'=>$master, 'bool'=>false])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findVirtualToMasterByTraderTotal($date1, $date2, $id, $master)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.toMsisdn = :master')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'master'=>$master,'bool'=>false])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findVirtualToMasterByPosTotal($date1, $date2, $id, $master)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.pointofsale', 'pointofsale')
            ->andWhere('pointofsale.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.toMsisdn = :master')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'master'=>$master, 'bool'=>false])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findVirtualFromMasterToTrader($date1, $date2, $id, $master)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.fromMsisdn = :master')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'master'=>$master, 'bool'=>false])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findVirtualFromMasterToTraderTotal($date1, $date2, $id, $master)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.fromMsisdn = :master')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'master'=>$master, 'bool'=>false])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findVirtualFromMasterToPosTotal($date1, $date2, $id, $master)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.pointofsale', 'pointofsale')
            ->andWhere('pointofsale.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.fromMsisdn = :master')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'master'=>$master, 'bool'=>false])
            ->getQuery()
            ->getSingleScalarResult()
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

    /**
     *
     * @param $date1
     * @param $date2
     * @return mixed
     */
    public function findGiveInBankByTraders($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title = :profile')
            ->andwhere('t.fromMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'profile'=>'CAGNT', 'bool'=>true])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveInBankByTradersByDay($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title = :profile')
            ->andwhere('t.fromMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'profile'=>'CAGNT', 'bool'=>true])
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveOutBankByTraders($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title = :profile')
            ->andwhere('t.toMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'profile'=>'CAGNT', 'bool'=>true])
            ->getQuery()
            ->getResult()
            ;
    }


    public function findGiveOutBankByTradersByDay($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title = :profile')
            ->andwhere('t.toMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'profile'=>'CAGNT', 'bool'=>true])
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }


    public function findGiveInBankByPointofsales($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title != :profile')
            ->andwhere('t.fromMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'profile'=>'CAGNT', 'bool'=>true])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveInBankByPointofsalesByDay($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title != :profile')
            ->andwhere('t.fromMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'profile'=>'CAGNT', 'bool'=>true])
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }


    public function findGiveOutBankByPointofsales($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title != :profile')
            ->andwhere('t.toMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'profile'=>'CAGNT', 'bool'=>true])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveOutBankByPointofsalesByDay($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title != :profile')
            ->andwhere('t.toMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'profile'=>'CAGNT', 'bool'=>true])
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveInBank($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, COUNT(t.id) as total')
            ->innerJoin('t.toMsisdn', 's')
            ->andwhere('t.fromMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'bool'=>true])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveInBankByDay($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.toMsisdn', 's')
            ->andwhere('t.fromMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'bool'=>true])
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveOutBank($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, COUNT(t.id) as total')
            ->innerJoin('t.fromMsisdn', 's')
            ->andwhere('t.toMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'bool'=>true])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveOutBankByDay($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.fromMsisdn', 's')
            ->andwhere('t.toMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isBankGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'bool'=>true])
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleByTraders($date1, $date2, $profile)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, COUNT(t.id) as total')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title = :profile')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'profile' => $profile])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleByTradersByDay($date1, $date2, $profile)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, COUNT(t.id) as total, DATE(t.transactionAt) as day')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title = :profile')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'profile' => $profile])
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findSaleByTrader($date1, $date2, $id, $master)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('s.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.toMsisdn != :master')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'id' => $id, 'master'=> $master])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findSaleReceiveByTrader($date1, $date2, $id, $master)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('s.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.fromMsisdn != :master')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'id' => $id, 'master'=> $master])
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findSaleByTraderByDay($date1, $date2, $trader)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, COUNT(t.id) as total, DATE(t.transactionAt) as day')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('s.trader = :trader')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'trader' => $trader])
            ->groupBy('day')
            ->getQuery()
            ->getResult()
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
