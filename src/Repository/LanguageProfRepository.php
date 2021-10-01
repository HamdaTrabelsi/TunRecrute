<?php

namespace App\Repository;

use App\Entity\LanguageProf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LanguageProf|null find($id, $lockMode = null, $lockVersion = null)
 * @method LanguageProf|null findOneBy(array $criteria, array $orderBy = null)
 * @method LanguageProf[]    findAll()
 * @method LanguageProf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LanguageProfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LanguageProf::class);
    }

    // /**
    //  * @return LanguageProf[] Returns an array of LanguageProf objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LanguageProf
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
