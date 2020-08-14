<?php

namespace App\Repository;

use App\Entity\Township;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Township|null find($id, $lockMode = null, $lockVersion = null)
 * @method Township|null findOneBy(array $criteria, array $orderBy = null)
 * @method Township[]    findAll()
 * @method Township[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TownshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Township::class);
    }

    // /**
    //  * @return Township[] Returns an array of Township objects
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
    public function findOneBySomeField($value): ?Township
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
