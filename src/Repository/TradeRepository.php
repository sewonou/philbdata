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
     * Retourne la valeur des recharges faite en banque par l'univers regrouper par mois
     * @param $value
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

    /**
     * Retourne les give reçus par un POS
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     */
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

    /**
     * Retourne les give envoyé par un POS
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     */
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


    /*COMMERCIAL UNIQUE*/

    /**
     * Retourne les give reçu par un commercial
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     */
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

    /**
     * Retourne les gives envoyés par un commercial
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     */
    public function findGiveSendByTrader($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false])
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Retourne les give envoyé à une banque  par un commercial en détails
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     */
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

    /**
     * Retourne le total des  give envoyés en banque par un commercial
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
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

    /**
     * Retourne les give recu de la banque par un commercial sous forme de détails
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     */
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

    /**
     * Retourne le total des give reçu de la banque par un commercial
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
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

    /**
     * Retourne les Give envoyés aux POS de l'univers par un commercial
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     */
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

    /**
     * Retourne le total des Give envoyé au POS de l'univers par un commercial
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
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

    /**
     * Retourne les open give envoyé par un commercial
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     */
    public function findOpenInGiveByTrader($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.toMsisdn is not null')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>true])
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Retourne le total des open give envoyé par un commercial
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     */
    public function findOpenGiveByTraderTotal($date1, $date2, $id)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.fromMsisdn', 's')
            ->innerJoin('s.trader', 'pointofsale')
            ->andWhere('pointofsale.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.toMsisdn is not null')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>true])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findVirtualFromPosToTrader($date1, $date2, $id, $master)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.type', 'type')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.trader', 'trader')
            ->andWhere('trader.id = :id')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('type.title = :val' )
            ->andwhere('t.fromMsisdn is  not null')
            ->andWhere('t.fromMsisdn != :master')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false, 'master' => $master])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findVirtualFromPosToTraderTotal($date1, $date2, $id, $master)
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
            ->andWhere('t.fromMsisdn != :master')
            ->andWhere('t.isBankGive = :bool')
            ->andWhere('t.isOpenGive = :bool')
            ->setParameters(['date1'=>$date1, 'date2'=>$date2, 'val'=> 'GIVE', 'id'=>$id, 'bool'=>false, 'master' => $master])
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
            ->andwhere('t.toMsisdn is  not null')
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

    /*POINTOFSALE UNIQUE*/

    /**
     * Retourne le total des give envoyés à la banque par un POS
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
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


    /**
     * Retourne les gives reçu de la banque par un POS
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
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

    /**
     * Retourne le total des  openGive envoyés par un POS
     * @param $date1
     * @param $date2
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOpenGiveByPosTotal($date1, $date2, $id)
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

    /**
     *
     * @param $date1
     * @param $date2
     * @return mixed
     */
    public function findOpenGiveInByTraders($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title = :profile')
            ->andwhere('t.fromMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isOpenGive = :bool')
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

    public function findOpenGiveInByTradersByDay($date1, $date2)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as amount, DATE(t.transactionAt) as day, COUNT(t.id) as total')
            ->innerJoin('t.toMsisdn', 's')
            ->innerJoin('s.profile', 'p')
            ->where('p.title = :profile')
            ->andwhere('t.fromMsisdn is null')
            ->andWhere('DATE(t.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('t.isOpenGive = :bool')
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

    /**
     * Retourne le total des  give reçu de la banque par le point de vente durant la période
     * @param $date1
     * @param $date2
     * @return mixed
     */

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

    /**
     * Retourne le total des  give reçu de la banque par le point de vente regroupé par jour
     * @param $date1
     * @param $date2
     * @return mixed
     */
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

    /**
     * Retourne le total des  give envoyé à la banque par le point de vente durant la période
     * @param $date1
     * @param $date2
     * @return mixed
     */
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

    /**
     * Retourne le total des  give envoyé à la banque par le point de vente regroupé par jour
     * @param $date1
     * @param $date2
     * @return mixed
     */
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


    /*GIVE BANQUE*/

    /**
     * Retourne le montant total de Give envoyé à la banque par le réseau durant la période (retrait de liquidité)
     * date début @param $date1
     * date fin @param $date2
     * @return mixed
     */
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

    /**
     * Retourne le montant total de give envoyé à la baque par le réseau par jour (retrait de liquidité)
     * @param $date1
     * @param $date2
     * @return mixed
     */
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

    /**
     * Retourne le montant de give reçu par le réseau de la banque (achat de virtuel)
     * @param $date1
     * @param $date2
     * @return mixed
     */
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

    /**
     * Retourne le montant de give reçu par le réseau de la banque par jour (achat de virtuel)
     * @param $date1
     * @param $date2
     * @return mixed
     *
     */
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

    /**
     * Retourne les ventes effectué par les commerciaux
     * @param $date1
     * @param $date2
     * @param $profile
     * @return mixed
     */
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

    /**
     * Retourne les ventes effectué par les commerciaux regoroupé par jour
     * @param $date1
     * @param $date2
     * @param $profile
     * @return mixed
     */
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
