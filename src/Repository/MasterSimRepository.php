<?php

namespace App\Repository;

use App\Entity\MasterSim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MasterSim|null find($id, $lockMode = null, $lockVersion = null)
 * @method MasterSim|null findOneBy(array $criteria, array $orderBy = null)
 * @method MasterSim[]    findAll()
 * @method MasterSim[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasterSimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MasterSim::class);
    }

    public function findOne()
    {
    }

    // /**
    //  * @return MasterSim[] Returns an array of MasterSim objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MasterSim
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
