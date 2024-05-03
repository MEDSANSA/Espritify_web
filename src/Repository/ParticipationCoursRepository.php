<?php

namespace App\Repository;

use App\Entity\ParticipationCours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParticipationCours>
 *
 * @method ParticipationCours|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipationCours|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipationCours[]    findAll()
 * @method ParticipationCours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationCoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipationCours::class);
    }

//    /**
//     * @return ParticipationCours[] Returns an array of ParticipationCours objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ParticipationCours
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}