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
            ->setParameters(['val'=>true, 'id'=>$id, 'profile' => $profile])
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
            ->andWhere('p.isActive = :val')
            ->andWhere('s.isActive = :val')
            ->andWhere('t.id = :id')
            ->setParameters(['val'=>$val, 'id'=>$id, 'profile'=>$profile])
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
