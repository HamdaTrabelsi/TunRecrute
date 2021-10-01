<?php

namespace App\Repository;

use App\Entity\ProfSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfSkill[]    findAll()
 * @method ProfSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfSkill::class);
    }

    // /**
    //  * @return ProfSkill[] Returns an array of ProfSkill objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProfSkill
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
