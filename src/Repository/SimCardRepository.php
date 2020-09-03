<?php

namespace App\Repository;

use App\Entity\Profile;
use App\Entity\SimCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SimCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method SimCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method SimCard[]    findAll()
 * @method SimCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SimCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SimCard::class);
    }

    public function setInactive(?Profile $profile, ?string $date)
    {
        return $this->createQueryBuilder('s')
            ->update()
            ->set('s.isActive', 'false')
            ->where('s.profile = :val')
            ->andWhere('DATE(s.updateAt)<> :date')
            ->setParameters(['val'=>$profile, 'date'=> $date])
            ->getQuery()
            ->execute();
    }

    public function findPointofsaleLastUpdate()
    {
        return $this->createQueryBuilder('s')
        ->select('DATE(MAX(s.updateAt)) as lastDate')
        ->setMaxResults(1)
        ->getQuery()
        ->getSingleScalarResult();
    }

    public function findPointofsale()
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.profile', 'p')
            ->andWhere('p.title = :val1')
            ->andWhere('p.title = :val2')
            ->setParameters(['val1' => 'AGNT', 'val2' => 'DISTRO'])
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return SimCard[] Returns an array of SimCard objects
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
    public function findOneBySomeField($value): ?SimCard
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
