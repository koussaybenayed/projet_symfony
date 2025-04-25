<?php

namespace App\Repository;

use App\Entity\Reduction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reduction>
 */
class ReductionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reduction::class);
    }

    /**
     * Recherche des réductions par leur code.
     *
     * @param string $search Le terme de recherche pour le code
     * @return Reduction[] Tableau de réductions correspondantes
     */
    public function findByCode(string $search): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.code LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('r.id', 'ASC') // Optionnel, selon si tu veux trier les résultats
            ->getQuery()
            ->getResult();
    }

    // Exemple de méthode commentée qui permet de récupérer des réductions par un autre critère (à adapter si nécessaire).
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('r')
    //         ->andWhere('r.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('r.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // Exemple de méthode commentée pour récupérer une réduction spécifique par un autre critère (à adapter si nécessaire).
    // public function findOneBySomeField($value): ?Reduction
    // {
    //     return $this->createQueryBuilder('r')
    //         ->andWhere('r.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
