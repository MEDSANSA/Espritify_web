<?php

namespace App\Repository;

use App\Entity\Certification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Certification>
 *
 * @method Certification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Certification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Certification[]    findAll()
 * @method Certification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Certification::class);
    }

//    /**
//     * @return Certification[] Returns an array of Certification objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Certification
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
