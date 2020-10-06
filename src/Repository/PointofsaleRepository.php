<?php

namespace App\Repository;

use App\Entity\Pointofsale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @method Pointofsale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pointofsale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pointofsale[]    findAll()
 * @method Pointofsale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointofsaleRepository extends ServiceEntityRepository
{
    public function __construct(?ManagerRegistry $registry)
    {
        parent::__construct($registry, Pointofsale::class);
    }

    /**
     * @param $value
     * @param string|null $profile
     * @return Pointofsale[] Returns an array of Pointofsale objects
     */

    public function findPointofsaleWithoutProfile($value, ?string  $profile)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn', 's')
            ->innerJoin('s.profile', 'pro')
            ->where('pro.title != :profile')
            ->andWhere('s.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->setParameters(['val'=>$value, 'profile'=>$profile])
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $value
     * @return Pointofsale[] Returns an array of Pointofsale objects
     */

    public function findPointofsaleWithTrader($value)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.controls', 'c')
            ->innerJoin('c.trader', 't')
            ->andWhere('c.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findInDistrict(?string $id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('p.district', 'd')
            ->andWhere('p.isActive = :val')
            ->andWhere('s.isActive = :val')
            ->andWhere('d.id = :id')
            ->setParameters(['val'=>true, 'id'=>$id])
            ->getQuery()
            ->getResult()
            ;
    }
    public function findPointofsaleInDistrict(?string $id, ?string $profile, ?bool $val)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('s.profile', 'pr')
            ->innerJoin('p.district', 'd')
            ->andWhere('pr.title = :profile')
            ->andWhere('p.isActive = :val')
            ->andWhere('s.isActive = :val')
            ->andWhere('d.id = :id')
            ->setParameters(['val'=> $val, 'id'=>$id, 'profile' => $profile])
            ->getQuery()
            ->getResult()
        ;
    }

    public function findInTownship(?string $id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('p.district', 'd')
            ->innerJoin('d.township', 't')
            ->andWhere('p.isActive = :val')
            ->andWhere('s.isActive = :val')
            ->andWhere('t.id = :id')
            ->setParameters(['val'=>true, 'id'=>$id])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPointofsaleInTownship(?string $id, ?string  $profile, ?bool $val)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('s.profile', 'pr')
            ->innerJoin('p.district', 'd')
            ->innerJoin('d.township', 't')
            ->andWhere('pr.title = :profile')
            ->andWhere('p.isActive = :val')
            ->andWhere('s.isActive = :val')
            ->andWhere('t.id = :id')
            ->setParameters(['val'=>$val, 'id'=>$id, 'profile'=>$profile])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findInPrefecture(?string $id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('p.district', 'd')
            ->innerJoin('d.township', 't')
            ->innerJoin('t.prefecture', 'pf')
            ->andWhere('s.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->andWhere('pf.id = :id')
            ->setParameters(['val'=>true, 'id'=>$id])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPointofsaleInPrefecture(?string $id, ?string  $profile, ?bool $val)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('s.profile', 'pr')
            ->innerJoin('p.district', 'd')
            ->innerJoin('d.township', 't')
            ->innerJoin('t.prefecture', 'pf')
            ->andWhere('pr.title = :profile')
            ->andWhere('s.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->andWhere('pf.id = :id')
            ->setParameters(['val'=>$val, 'id'=>$id, 'profile'=>$profile])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findInTown(?string $id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('p.district', 'd')
            ->innerJoin('d.township', 't')
            ->innerJoin('t.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->andWhere('s.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->andWhere('tw.id = :id')
            ->setParameters(['val'=>true, 'id'=>$id])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPointofsaleInTown(?string $id, ?string  $profile, ?bool $val)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('s.profile', 'pr')
            ->innerJoin('p.district', 'd')
            ->innerJoin('d.township', 't')
            ->innerJoin('t.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->andWhere('pr.title = :profile')
            ->andWhere('s.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->andWhere('tw.id = :id')
            ->setParameters(['val'=>$val, 'id'=>$id, 'profile'=>$profile])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findInRegion(?string $id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('p.district', 'd')
            ->innerJoin('d.township', 't')
            ->innerJoin('t.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->andWhere('s.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->andWhere('r.id = :id')
            ->setParameters(['val'=>true, 'id'=>$id])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPointofsaleInRegion(?string $id, ?string  $profile, ?bool $val)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('s.profile', 'pr')
            ->innerJoin('p.district', 'd')
            ->innerJoin('d.township', 't')
            ->innerJoin('t.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->andWhere('pr.title = :profile')
            ->andWhere('s.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->andWhere('r.id = :id')
            ->setParameters(['val'=>$val, 'id'=>$id, 'profile'=>$profile])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findInZone(?string $id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('p.district', 'd')
            ->innerJoin('d.township', 't')
            ->innerJoin('t.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->innerJoin('r.zone', 'z')
            ->andWhere('s.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->andWhere('z.id = :id')
            ->setParameters(['val'=>true, 'id'=>$id])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPointofsaleInZone(?string $id, ?string  $profile, ?bool $val)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.msisdn',  's')
            ->innerJoin('s.profile', 'pr')
            ->innerJoin('p.district', 'd')
            ->innerJoin('d.township', 't')
            ->innerJoin('t.prefecture', 'pf')
            ->innerJoin('pf.town', 'tw')
            ->innerJoin('tw.region', 'r')
            ->innerJoin('r.zone', 'z')
            ->andWhere('s.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->andWhere('z.id = :id')
            ->andWhere('pr.title = :profile')
            ->setParameters(['val'=>$val, 'id'=>$id, 'profile'=>$profile])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPointofsalesPeriodInput(?bool $val, $date1, $date2)
    {
        //dump($date1, $date2);
        return $this->createQueryBuilder('pos')
            ->select("pos as pointofsale, SUM(s.dComm) as dComm, SUM(s.posComm) as posComm, SUM(s.amount) as amount")
            ->innerJoin('pos.msisdn', 'sim')
            ->innerJoin('sim.sales', 's')
            ->andWhere('sim.isActive = :val')
            ->andWhere('pos.isActive = :val')
            ->andWhere('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->setParameters(['val'=>$val, 'date1'=>$date1, 'date2'=>$date2])
            ->orderBy('dComm', 'ASC')
            ->groupBy('pos.id')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findPointofsaleComm(?bool $val, $date1, $date2, $id)
    {
        //dump($date1, $date2);
        return $this->createQueryBuilder('pos')
            ->select("SUM(s.dComm) as dComm, SUM(s.posComm) as posComm")
            ->innerJoin('pos.msisdn', 'sim')
            ->innerJoin('sim.sales', 's')
            ->andWhere('sim.isActive = :val')
            ->andWhere('pos.isActive = :val')
            ->andWhere('DATE(s.transactionAt) BETWEEN :date1 AND :date2')
            ->andWhere('pos.id = :id')
            ->setParameters(['val'=>$val, 'date1'=>$date1, 'date2'=>$date2, 'id'=>$id])
            ->groupBy('pos.id')
            ->getQuery()
            ->getResult()
            ;
    }



    /*
    public function findOneBySomeField($value): ?Pointofsale
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
