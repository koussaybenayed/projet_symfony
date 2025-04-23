<?php

namespace App\Repository;

use App\Entity\Response;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Response>
 */
class ResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Response::class);
    }
    

   
public function findBySearchTerm(string $term): array
{
    return $this->createQueryBuilder('r')
        ->leftJoin('r.reclamation', 'rec')
        ->leftJoin('rec.user', 'u')
        ->addSelect('rec', 'u')
        
        ->Where('rec.description LIKE :term')
        ->orWhere('rec.status LIKE :term')
       
        ->setParameter('term', '%' . $term . '%')
        ->orderBy('r.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
}
    
    //    /**
    //     * @return Response[] Returns an array of Response objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Response
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
