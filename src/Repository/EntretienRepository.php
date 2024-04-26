<?php

namespace App\Repository;

use App\Entity\Entretien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entretien>
 *
 * @method Entretien|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entretien|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entretien[]    findAll()
 * @method Entretien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntretienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entretien::class);
    }
    /**
     * Find Entretien by id_user and id_offre.
     *
     * @param int $userId
     * @param int $offreId
     * @return Entretien|null
     */
    public function findByUserIdAndOffreId(int $userId, int $offreId): ?Entretien
    {
        return $this->findOneBy(['id_user' => $userId, 'id_stage' => $offreId]);
    }
    public function findInterviewsByIdUserAndDateGreaterThan($id, \DateTimeInterface $date)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.id_user = :id')
            ->andWhere('e.date > :date')
            ->setParameter('id', $id)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Entretien[] Returns an array of Entretien objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Entretien
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
