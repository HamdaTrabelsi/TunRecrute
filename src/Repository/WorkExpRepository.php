<?php

namespace App\Repository;

use App\Entity\WorkExp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkExp|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkExp|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkExp[]    findAll()
 * @method WorkExp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkExpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkExp::class);
    }

    // /**
    //  * @return WorkExp[] Returns an array of WorkExp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkExp
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
