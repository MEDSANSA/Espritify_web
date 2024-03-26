<?php

namespace App\Repository;

use App\Entity\DossierStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DossierStage>
 *
 * @method DossierStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method DossierStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method DossierStage[]    findAll()
 * @method DossierStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DossierStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DossierStage::class);
    }

//    /**
//     * @return DossierStage[] Returns an array of DossierStage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DossierStage
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
