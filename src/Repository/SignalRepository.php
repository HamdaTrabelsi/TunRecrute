<?php

namespace App\Repository;

use App\Entity\Signal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Signal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Signal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Signal[]    findAll()
 * @method Signal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Signal::class);
    }

    /*
     * @return Query
     */
    public function findallsignal(){
        return $this->createQueryBuilder('l')
            ->orderBy('l.time', 'DESC')
            ->getQuery();
    }

    public function CountAll(){
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    // /**
    //  * @return Signal[] Returns an array of Signal objects
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
    public function findOneBySomeField($value): ?Signal
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
