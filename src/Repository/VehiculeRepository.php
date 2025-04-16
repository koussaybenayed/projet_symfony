<?php

namespace App\Repository;

use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicule>
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }

    /**
     * Trouve les véhicules par type.
     *
     * @param string $type
     * @return Vehicule[] Returns an array of Vehicule objects
     */
    public function findByTypeVehicule(string $type): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.typeVehicule = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }

    // Exemple de méthode pour trouver un véhicule par un autre champ
    // public function findOneBySomeField($value): ?Vehicule
    // {
    //     return $this->createQueryBuilder('v')
    //         ->andWhere('v.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}