<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }


    /*
 * @return Query
 */
    public function findallannonce(){
        return $this->createQueryBuilder('l')
            ->orderBy('l.posted', 'DESC')
            ->getQuery();
    }

    public function CountAll(){
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function GetSample()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.posted', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    public function CountDomaine()
    {
        return $this->createQueryBuilder('a')
            ->addselect('a.domaine, COUNT(a.domaine) AS mycount')
            ->groupBy('a.domaine')
            ->getQuery()
            ->getResult()
            ;
    }

    public function SearchByfields($title, $field, $location,$contract,$diploma,$exp)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.title LIKE :title')
            ->andWhere('a.domaine LIKE :field')
            ->andWhere('a.adresseTravail LIKE :location')
            ->andWhere('a.contrat LIKE :contract')
            ->andWhere('a.diplome LIKE :diploma')
            ->andWhere('a.experience LIKE :experience')

            ->setParameter('location', '%'.$location.'%')
            ->setParameter('title', '%'.$title.'%')
            ->setParameter('field', '%'.$field.'%')
            ->setParameter('contract', '%'.$contract.'%')
            ->setParameter('diploma', '%'.$diploma.'%')
            ->setParameter('experience', '%'.$exp.'%')

            ->orderBy('a.posted', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Annonce[] Returns an array of Annonce objects
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
    public function findOneBySomeField($value): ?Annonce
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
