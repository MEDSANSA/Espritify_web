<?php

namespace App\Repository;

use App\Entity\Offrestage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offrestage>
 *
 * @method Offrestage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offrestage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offrestage[]    findAll()
 * @method Offrestage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offrestage::class);
    }
    public function countApplicationsPerOffer(): array
    {
        $qb = $this->createQueryBuilder('o')
            ->select('o.id, o.nom_soc, COUNT(d.id_user) AS num_applications')
            ->leftJoin('o.dossierStages', 'd')
            ->groupBy('o.id');

        return $qb->getQuery()->getResult();
    }
    
//    /**
//     * @return Offrestage[] Returns an array of Offrestage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offrestage
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}