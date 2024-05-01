<?php

namespace App\Repository;

use App\Entity\ReponseRec;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReponseRec>
 *
 * @method ReponseRec|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseRec|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseRec[]    findAll()
 * @method ReponseRec[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseRecRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseRec::class);
    }

//    /**
//     * @return ReponseRec[] Returns an array of ReponseRec objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReponseRec
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
