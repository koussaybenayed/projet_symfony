<?php

namespace App\Repository;

use App\Entity\Livraison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livraison::class);
    }

    /**
     * @return Livraison[] Returns an array of Livraison objects
     */
    public function findBySearchCriteria(?string $searchTerm, ?string $status = null): array
    {
        $qb = $this->createQueryBuilder('l');
        
        if ($searchTerm) {
            $qb->where('l.id_livraisons LIKE :searchTerm')
                ->orWhere('l.poids_colis LIKE :searchTerm')
                ->orWhere('l.delivery_cost LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }
        
        if ($status && $status !== 'all') {
            $qb->andWhere('l.destination_status = :status')
                ->setParameter('status', $status);
        }
        
        return $qb->orderBy('l.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    //    /**
    //     * @return Livraison[] Returns an array of Livraison objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
    
    //    public function findOneBySomeField($value): ?Livraison
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}