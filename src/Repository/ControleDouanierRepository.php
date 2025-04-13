<?php
namespace App\Repository;

use App\Entity\ControleDouanier;
use App\Entity\Livraison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class ControleDouanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ControleDouanier::class);
    }

    public function findBySearchCriteria(
        ?string $searchTerm = null,
        ?string $status = null,
        ?\DateTimeInterface $dateFrom = null,
        ?\DateTimeInterface $dateTo = null
    ): array {
        $qb = $this->createSearchQueryBuilder($searchTerm, $status, $dateFrom, $dateTo);
        
        return $qb->getQuery()->getResult();
    }

    private function createSearchQueryBuilder(
        ?string $searchTerm = null,
        ?string $status = null,
        ?\DateTimeInterface $dateFrom = null,
        ?\DateTimeInterface $dateTo = null
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.livraison', 'l')
            ->addSelect('l')  // Précharge les données de livraison
            ->orderBy('c.date_controle', 'DESC');

        $this->applySearchTerm($qb, $searchTerm);
        $this->applyStatusFilter($qb, $status);
        $this->applyDateFilters($qb, $dateFrom, $dateTo);

        return $qb;
    }
    // src/Repository/ControleDouanierRepository.php


public function findAllPays(): array
{
    return $this->createQueryBuilder('c')
        ->select('DISTINCT c.pays_douane')
        ->where('c.pays_douane IS NOT NULL')
        ->orderBy('c.pays_douane', 'ASC')
        ->getQuery()
        ->getSingleColumnResult();
}

    private function applySearchTerm(QueryBuilder $qb, ?string $searchTerm): void
    {
        if ($searchTerm) {
            $qb->andWhere('c.pays_douane LIKE :searchTerm OR c.commentaires LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }
    }

    private function applyStatusFilter(QueryBuilder $qb, ?string $status): void
    {
        if ($status && $status !== 'all') {
            $qb->andWhere('c.statut = :status')
                ->setParameter('status', $status);
        }
    }

    private function applyDateFilters(
        QueryBuilder $qb,
        ?\DateTimeInterface $dateFrom,
        ?\DateTimeInterface $dateTo
    ): void {
        if ($dateFrom) {
            $qb->andWhere('c.date_controle >= :dateFrom')
                ->setParameter('dateFrom', $dateFrom);
        }

        if ($dateTo) {
            $qb->andWhere('c.date_controle <= :dateTo')
                ->setParameter('dateTo', $dateTo);
        }
    }

    public function findWithLivraison(int $id): ?ControleDouanier
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.livraison', 'l')
            ->addSelect('l')
            ->andWhere('c.id_controle = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function findByLivraison(Livraison $livraison): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.livraison = :livraison')
            ->setParameter('livraison', $livraison)
            ->orderBy('c.date_controle', 'DESC')
            ->getQuery()
            ->getResult();
    }
}