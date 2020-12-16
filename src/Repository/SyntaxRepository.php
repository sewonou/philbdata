<?php

namespace App\Repository;

use App\Entity\Syntax;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Syntax|null find($id, $lockMode = null, $lockVersion = null)
 * @method Syntax|null findOneBy(array $criteria, array $orderBy = null)
 * @method Syntax[]    findAll()
 * @method Syntax[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SyntaxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Syntax::class);
    }

    // /**
    //  * @return Syntax[] Returns an array of Syntax objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Syntax
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
