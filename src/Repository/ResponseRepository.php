<?php

namespace App\Repository;

use App\Entity\Response;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Response>
 *
 * @method Response|null find($id, $lockMode = null, $lockVersion = null)
 * @method Response|null findOneBy(array $criteria, array $orderBy = null)
 * @method Response[]    findAll()
 * @method Response[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Response::class);
    }

    /**
     * Search and sort responses based on the given criteria.
     * 
     * @param string|null $searchTerm  The term to search in responses.
     * @param string|null $sortBy     The field to sort by.
     * @param string      $sortOrder  The sorting order: 'asc' or 'desc'.
     * 
     * @return Response[] The list of responses matching the search and sort criteria.
     */
    public function findBySearchTerm(string $term): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.reclamation', 'rec')
            ->leftJoin('rec.user', 'u')
            ->where('r.response_text LIKE :term OR rec.description LIKE :term ')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Get the latest responses, limited by the max results.
     * 
     * @param int $maxResults The maximum number of results to retrieve.
     * 
     * @return Response[] The latest responses.
     */
    public function findLatest(int $maxResults = 10): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    /**
     * Count the total number of responses.
     * 
     * @return int The total number of responses.
     */
    public function countResponses(): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Find responses related to a specific reclamation description.
     * 
     * @param string $description The reclamation description to filter by.
     * 
     * @return Response[] The list of responses related to the given reclamation description.
     */
    public function findByReclamationDescription(string $description): array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.reclamation', 'rec')
            ->andWhere('rec.description LIKE :description')
            ->setParameter('description', '%' . $description . '%')
            ->getQuery()
            ->getResult();
    }
}
