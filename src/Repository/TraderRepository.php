<?php

namespace App\Repository;

use App\Entity\Trader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trader|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trader|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trader[]    findAll()
 * @method Trader[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trader::class);
    }

    public function findTraderPointofsale(?Trader $trader, ?bool $value)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.controls', 'c')
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

    public function findTraderPointofsaleByProfile(?int $id, ?bool $value, ?string $profile)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.controls', 'c')
            ->innerJoin('c.pointofsale', 'p')
            ->innerJoin('p.msisdn', 's')
            ->innerJoin('s.profile', 'pr')
            ->andWhere('c.isActive = :val')
            ->andWhere('p.isActive = :val')
            ->andWhere('s.isActive = :val')
            ->andWhere('t.id = :id')
            ->andWhere('pr.title = :profile')
            ->setParameters(['val'=>$value, 'id'=>$id, 'profile' => $profile])
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Trader[] Returns an array of Trader objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Trader
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
