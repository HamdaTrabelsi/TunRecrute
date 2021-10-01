<?php

namespace App\Repository;

use App\Entity\Candidature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Candidature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidature[]    findAll()
 * @method Candidature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidature::class);
    }



    public function findByExampleField($EmployerId)
    {
        return $this->createQueryBuilder('c')
            ->join('c.Annonce','a')
            ->join('a.User','u')
            ->where('u.id = :val')
            ->setParameter('val', $EmployerId)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findPastApps($EmployerId)
    {
        return $this->createQueryBuilder('c')
            ->join('c.Annonce','a')
            ->join('a.User','u')
            ->where('u.id = :val')
            ->andWhere('c.status IS NOT NULL')
            ->orderBy('c.Date','DESC')
            ->setParameter('val', $EmployerId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function CountAll(){
        return $this->createQueryBuilder('u')
            ->select('count(u.Date)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    /*
    public function findOneBySomeField($value): ?Candidature
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
