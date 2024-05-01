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
    /**
     * Find DossierStage by id_user and id_offre.
     *
     * @param int $userId
     * @param int $offreId
     * @return DossierStage|null
     */
    public function findByUserIdAndOffreId(int $userId, int $offreId): ?DossierStage
    {
        return $this->findOneBy(['id_user' => $userId, 'id_offre' => $offreId]);
    }

    public function findDossiersByUserIdWithOffreStage($userId)
{
    $qb = $this->createQueryBuilder('d')
        ->leftJoin('d.id_offre', 'o', 'WITH', 'o.id = d.id_offre')
        ->where('d.id_user = :userId')
        ->setParameter('userId', $userId);

    $qbRightJoin = clone $qb;
    $qbRightJoin->select('d') // Selecting the whole DossierStage entity
        ->leftJoin('d.id_offre', 'o2', 'WITH', 'o2.id = d.id_offre')
        ->andWhere($qbRightJoin->expr()->isNull('d.id_offre')); // Assuming id_offre is the association with OffreStage

    $result = $qb->getQuery()->getResult();
    $resultRight = $qbRightJoin->getQuery()->getResult();

    // Merge the results of left join and right join
    $mergedResult = array_merge($result, $resultRight);

    return $mergedResult;
}
public function countRows(): int
    {
        return $this->createQueryBuilder('d')
            ->select('COUNT(d.id_user)')
            ->getQuery()
            ->getSingleScalarResult();
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