<?php

namespace App\Repository;

use App\Entity\Arret;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Arret|null find($id, $lockMode = null, $lockVersion = null)
 * @method Arret|null findOneBy(array $criteria, array $orderBy = null)
 * @method Arret[]    findAll()
 * @method Arret[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArretRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Arret::class);
    }

    // /**
    //  * @return Arret[] Returns an array of Arret objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Arret
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
