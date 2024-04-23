<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenement>
 *
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }
    /**
     * Récupère les événements ayant lieu pendant la semaine spécifiée.
     *
     * @param string $startOfWeek Date de début de la semaine au format 'Y-m-d'.
     * @return Evenement[] Les événements de la semaine.
     */
   /* public function findEventsForWeek(string $startOfWeek): array
    {
        $endOfWeek = date('Y-m-d', strtotime($startOfWeek . ' + 6 days'));

        return $this->createQueryBuilder('e')
            ->andWhere('e.dateDebut BETWEEN :startOfWeek AND :endOfWeek')
            ->setParameter('startOfWeek', $startOfWeek)
            ->setParameter('endOfWeek', $endOfWeek)
            ->getQuery()
            ->getResult();
    }
*/
   /* public function findEventsForWeek(string $startOfWeek): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.titre, e.description, e.dateDebut, c.intitule as clubName')
            ->leftJoin('e.club', 'c')
            ->where('e.dateDebut >= :startOfWeek')
            ->andWhere('e.dateDebut < :endOfWeek')
            ->setParameter('startOfWeek', $startOfWeek)
            ->setParameter('endOfWeek', date('Y-m-d', strtotime($startOfWeek . ' + 1 week')))
            ->getQuery()
            ->getResult();
    }*/



    public function findEventsForWeek(\DateTime $startDate): array
    {
        // Logique pour récupérer les événements pour la semaine à partir de $startDate
        // Par exemple :
         $endDate = clone $startDate;
         $endDate->modify('+7 days');
         return $this->createQueryBuilder('e')
             ->andWhere('e.date_debut >= :startDate')
             ->andWhere('e.date_fin < :endDate')
             ->setParameter('startDate', $startDate)
             ->setParameter('endDate', $endDate)
             ->getQuery()
             ->getResult();
    }

//    /**
//     * @return Evenement[] Returns an array of Evenement objects
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

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
