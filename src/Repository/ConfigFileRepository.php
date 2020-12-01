<?php

namespace App\Repository;

use App\Entity\ConfigFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConfigFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigFile[]    findAll()
 * @method ConfigFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigFile::class);
    }

    public function findConfigFile(string $value)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.category', 'fc')
            ->andWhere('fc.slug != :value')
            ->setParameters(['value'=>$value])
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findTransactionFile($value, $value2)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.category', 'fc')
            ->andWhere('fc.slug = :value')
            ->orWhere('fc.slug = :value2')
            ->setParameters(['value'=>$value, 'value2'=>$value2])
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return ConfigFile[] Returns an array of ConfigFile objects
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
    public function findOneBySomeField($value): ?ConfigFile
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
