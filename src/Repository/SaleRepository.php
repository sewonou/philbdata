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

    public function findSaleByTrader($val, $trader, $date1, $date2)
    {
        return $this->createQueryBuilder('s')
            ->select("SUM(s.dComm) as dComm")
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.controls', 'c')
            ->innerJoin('c.trader', 't')
            ->andWhere('sim.isActive = :val')
            ->andWhere('pos.isActive = :val')
            ->andWhere('c.isActive = :val')
            ->andWhere('c.trader = :trader')
            ->andWhere('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->setParameters(['val'=>$val, 'trader'=>$trader, 'date1'=>$date1, 'date2'=>$date2])
            ->orderBy('t.id')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findSaleForTrader($val, $trader, $date1, $date2)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 'type')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.controls', 'c')
            ->innerJoin('c.trader', 't')
            ->where('type.title != :type')
            ->andWhere('sim.isActive = :val')
            ->andWhere('pos.isActive = :val')
            ->andWhere('c.isActive = :val')
            ->andWhere('c.trader = :trader')
            ->andWhere('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->setParameters(['type'=>'GIVECOM', 'val'=>$val, 'trader'=>$trader, 'date1'=>$date1, 'date2'=>$date2])
            ->orderBy('t.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleByTraderByDay($val, $trader, $date1, $date2)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, SUM(s.posComm) as posComm, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 'type')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.controls', 'c')
            ->innerJoin('c.trader', 't')
            ->where('type.title != :type')
            ->andWhere('sim.isActive = :val')
            ->andWhere('pos.isActive = :val')
            ->andWhere('c.isActive = :val')
            ->andWhere('c.trader = :trader')
            ->andWhere('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->setParameters(['type'=>'GIVECOM', 'val'=>$val, 'trader'=>$trader, 'date1'=>$date1, 'date2'=>$date2])
            ->orderBy('t.id')
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleByTraderByDayWithLimit($val, $trader, $limit)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 'type')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.controls', 'c')
            ->innerJoin('c.trader', 't')
            ->where('type.title != :type')
            ->andWhere('sim.isActive = :val')
            ->andWhere('pos.isActive = :val')
            ->andWhere('c.isActive = :val')
            ->andWhere('c.trader = :trader')
            ->setParameters(['type'=>'GIVECOM', 'val'=>$val, 'trader'=>$trader])
            ->orderBy('day', 'DESC')
            ->setMaxResults($limit)
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleByMonth()
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, CONCAT(MONTHNAME(s.transactionAt), ' ', YEAR(s.transactionAt)) as day, t.title")
            ->innerJoin('s.type', 't')
            ->where('t.title != :val1')
            //->orwhere('t.title = :val2')
            ->setParameters(['val1'=>'GIVECOM'])
            ->orderBy('day', 'DESC')
            ->groupBy('day, s.type')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleByDay($date1, $date2)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andwhere('t.title != :val')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM'])
            ->orderBy('day', 'DESC')
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGiveComByDay($date1, $date2)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andwhere('t.title = :val')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM'])
            ->orderBy('day', 'DESC')
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findSaleByDate($startDate, $endDate)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.posComm) as posComm, SUM(s.amount) as amount, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, DATE(s.transactionAt) as day, t.title")
            ->innerJoin('s.type', 't')
            ->groupBy('day')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleByDateLimit($limit)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.posComm) as posComm, SUM(s.amount) as amount, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->groupBy('day')
            ->orderBy('day', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findLastDate()
    {
        return $this->createQueryBuilder('s')
            ->select('DATE(s.transactionAt) as day')
            ->orderBy('day', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
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

    public function findDistinctMonth()
    {
        return $this->createQueryBuilder('s')
            ->select("SUM(s.dComm) as dComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, CONCAT(MONTHNAME(s.transactionAt), ' ', YEAR(s.transactionAt)) as day")
            ->groupBy('day')
            ->orderBy('day', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleByRegionByDay($date1, $date2)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, r.name as name, r.id as id")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andwhere('t.title != :val')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM'])
            ->orderBy('r.name', 'DESC')
            ->groupBy('r.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleByRegion($date1, $date2)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, r.name as name, r.id as id")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andwhere('t.title != :val')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM'])
            ->orderBy('r.name', 'DESC')
            ->groupBy('r.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInZone($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, z.id as id")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->innerJoin('r.zone', 'z')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('z.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=> $id])
            ->groupBy('z.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInZoneByDay($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, z.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->innerJoin('r.zone', 'z')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('z.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=> $id])
            ->groupBy('day, z.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInZoneWithLimit($id, $limit)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, z.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->innerJoin('r.zone', 'z')
            ->andWhere('t.title != :val')
            ->andWhere('z.id = :id')
            ->setParameters(['val'=> 'GIVECOM', 'id'=> $id])
            ->groupBy('day, z.id')
            ->setMaxResults($limit)
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    //STAT REGION

    public function findSaleInRegion($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, r.name as name, r.id as id")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('r.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('r.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInRegionByDay($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, r.name as name, r.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('r.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, r.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInRegionWithLimit($id, $limit)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, r.name as name, r.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->andWhere('t.title != :val')
            ->andWhere('r.id = :id')
            ->setParameters(['val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, r.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    //STAT TOWN

    public function findSaleInTown($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, tw.name as name, tw.id as id")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('tw.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('tw.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInTownByDay($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, tw.name as name, tw.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('tw.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, tw.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInTownWithLimit($id, $limit)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, tw.name as name, tw.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->andWhere('t.title != :val')
            ->andWhere('tw.id = :id')
            ->setParameters(['val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, tw.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    //STAT PREFECTURE

    public function findSaleInPrefecture($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, pf.name as name, pf.id as id")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('pf.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('pf.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInPrefectureByDay($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, pf.name as name, pf.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('pf.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, pf.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInPrefectureWithLimit($id, $limit)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, pf.name as name, pf.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->andWhere('t.title != :val')
            ->andWhere('pf.id = :id')
            ->setParameters(['val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, pf.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    //STAT SUR LES TOWNSHIP

    public function findSaleInTownship($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, r.name as name, tws.id as id")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('tws.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('tws.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInTownshipByDay($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, tws.name as name, tws.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('tws.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, tws.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInTownshipWithLimit($id, $limit)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, tws.name as name, tws.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->andWhere('t.title != :val')
            ->andWhere('tws.id = :id')
            ->setParameters(['val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, tws.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    //STAT SUR LES DISTRICT

    public function findSaleInDistrict($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, d.name as name, d.id as id")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('d.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('d.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInDistrictByDay($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, d.name as name, d.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('d.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, d.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInDistrictWithLimit($id, $limit)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, d.name as name, d.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->innerJoin('pos.district', 'd')
            ->innerJoin('d.township', 'tws')
            ->innerjoin('tws.prefecture', 'pf')
            ->andWhere('t.title != :val')
            ->andWhere('d.id = :id')
            ->setParameters(['val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, d.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    //STAT SUR LES DISTRICT

    public function findSaleInPointofsale($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, pos.name as name, pos.id as id")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('pos.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('pos.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInPointofsaleByDay($date1, $date2, $id)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, pos.name as name, pos.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->where('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.title != :val')
            ->andWhere('pos.id = :id')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, pos.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleInPointofsaleWithLimit($id, $limit)
    {
        return $this->createQueryBuilder('s')
            ->select("COUNT(s.id) as total, SUM(s.dComm) as dComm, SUM(s.amount) as amount, SUM(s.posComm) as posComm, SUM(s.dCommCalc) as dCommCalc, SUM(s.posCommCalc) as posCommCalc, pos.name as name, pos.id as id, DATE(s.transactionAt) as day")
            ->innerJoin('s.type', 't')
            ->innerJoin('s.msisdn', 'sim')
            ->innerJoin('sim.pointofsale', 'pos')
            ->andWhere('t.title != :val')
            ->andWhere('pos.id = :id')
            ->setParameters(['val'=> 'GIVECOM', 'id'=>$id])
            ->groupBy('day, pos.id')
            ->orderBy('DATE(s.transactionAt)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSaleIllegal($date)
    {
        return $this->createQueryBuilder('s')
            ->where('date(s.transactionAt) = :date')
            ->andWhere('s.dComm != s.dCommCalc')
            ->setParameters(['date'=>$date])
            ->getQuery()
            ->getResult()
            ;
    }
}
