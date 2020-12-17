<?php

namespace App\Repository;

use App\Entity\SyntaxCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SyntaxCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SyntaxCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SyntaxCategory[]    findAll()
 * @method SyntaxCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SyntaxCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SyntaxCategory::class);
    }

    // /**
    //  * @return SyntaxCategory[] Returns an array of SyntaxCategory objects
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
    public function findOneBySomeField($value): ?SyntaxCategory
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
