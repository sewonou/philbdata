<?php

namespace App\Repository;

use App\Entity\Pointofsale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pointofsale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pointofsale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pointofsale[]    findAll()
 * @method Pointofsale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointofsaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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