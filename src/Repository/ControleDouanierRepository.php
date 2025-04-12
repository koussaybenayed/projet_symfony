<?php

namespace App\Repository;

use App\Entity\ControleDouanier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ControleDouanier>
 */
class ControleDouanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ControleDouanier::class);
    }

    /**
     * Recherche des contrôles douaniers selon différents critères
     * 
     * @param string|null $searchTerm Terme de recherche (pays, commentaires)
     * @param string|null $status Statut du contrôle
     * @param \DateTime|null $dateFrom Date de début pour la recherche
     * @param \DateTime|null $dateTo Date de fin pour la recherche
     * @return ControleDouanier[] Returns an array of ControleDouanier objects
     */
    public function findBySearchCriteria(?string $searchTerm = null, ?string $status = null, ?\DateTime $dateFrom = null, ?\DateTime $dateTo = null): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.livraison', 'l')
            ->addSelect('l');
        
        // Recherche par terme (pays ou commentaires)
        if ($searchTerm) {
            $qb->andWhere('c.pays_douane LIKE :searchTerm OR c.commentaires LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }
        
        // Filtre par statut
        if ($status && $status !== 'all') {
            $qb->andWhere('c.statut = :status')
               ->setParameter('status', $status);
        }
        
        // Filtre par date - entre dateFrom et dateTo
        if ($dateFrom) {
            $qb->andWhere('c.date_controle >= :dateFrom')
               ->setParameter('dateFrom', $dateFrom);
        }
        
        if ($dateTo) {
            // Ajout d'un jour à dateTo pour inclure toute la journée jusqu'à minuit
            $dateToEnd = clone $dateTo;
            $dateToEnd->modify('+1 day');
            
            $qb->andWhere('c.date_controle < :dateTo')
               ->setParameter('dateTo', $dateToEnd);
        }
        
        return $qb->orderBy('c.date_controle', 'DESC')
                ->getQuery()
                ->getResult();
    }

    //    /**
    //     * @return ControleDouanier[] Returns an array of ControleDouanier objects
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

    //    public function findOneBySomeField($value): ?ControleDouanier
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}