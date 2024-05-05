<?php

namespace App\Repository;

use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Club>
 *
 * @method Club|null find($id, $lockMode = null, $lockVersion = null)
 * @method Club|null findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
    }
    // Méthode pour trier les clubs par un champ spécifique
    public function findByFieldSorted($field, $order = 'ASC')
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.'.$field, $order)
            ->getQuery()
            ->getResult();
    }

    // Méthode pour rechercher des les clubs par nom
    public function searchByAttributes($attributes)
    {
        $queryBuilder = $this->createQueryBuilder('c');

        // Boucle sur les attributs pour ajouter des conditions de recherche dynamiquement
        foreach ($attributes as $key => $value) {
            if (!empty($value)) {
                $queryBuilder->andWhere("c.$key LIKE :$key")
                    ->setParameter($key, "%$value%");
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }

    // Méthode pour récupérer les événements d'un club spécifique

//    /**
//     * @return Club[] Returns an array of Club objects
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

//    public function findOneBySomeField($value): ?Club
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
