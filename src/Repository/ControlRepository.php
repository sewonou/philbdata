<?php

namespace App\Repository;

use App\Entity\Control;
use App\Entity\Trader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Control|null find($id, $lockMode = null, $lockVersion = null)
 * @method Control|null findOneBy(array $criteria, array $orderBy = null)
 * @method Control[]    findAll()
 * @method Control[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ControlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Control::class);
    }

    public function findTraderPointofsale(?Trader $trader, ?bool $value)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.trader', 't')
            ->innerJoin('c.pointofsale', 'p')
            ->innerJoin('p.msisdn', 's')
            ->innerJoin('s.profile', 'pr')
            ->andWhere('c.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->andWhere('s.isActive = :val')
            ->andWhere('c.trader = :trader')
            ->setParameters(['val'=>$value, 'trader'=>$trader])
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findTraderPointofsaleByProfile(?Trader $trader, ?bool $value, ?string $profile)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.trader', 't')
            ->innerJoin('c.pointofsale', 'p')
            ->innerJoin('p.msisdn', 's')
            ->innerJoin('s.profile', 'pr')
            ->andWhere('c.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->andWhere('s.isActive = :val')
            ->andWhere('c.trader = :trader')
            ->andWhere('pr.title = :profile')
            ->setParameters(['val'=>$value, 'trader'=>$trader, 'profile' => $profile])
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Control[] Returns an array of Control objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Control
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
