<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    // Example method in UserRepository
public function findActiveUsers()
{
    return $this->createQueryBuilder('u')
        ->where('u.isActive = :active')
        ->setParameter('active', true)
        ->getQuery()
        ->getResult();
}
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Find users matching search criteria
     * 
     * @param string|null $username
     * @param string|null $firstname
     * @param string|null $lastname
     * @return User[] Returns an array of User objects
     */
    public function findBySearchCriteria(?string $username, ?string $firstname, ?string $lastname): array
    {
        $queryBuilder = $this->createQueryBuilder('u');
        
        // Add conditions based on provided parameters
        if ($username) {
            $queryBuilder
                ->andWhere('u.user_username LIKE :username')
                ->setParameter('username', '%' . $username . '%');
        }
        
        if ($firstname) {
            $queryBuilder
                ->andWhere('u.user_firstname LIKE :firstname')
                ->setParameter('firstname', '%' . $firstname . '%');
        }
        
        if ($lastname) {
            $queryBuilder
                ->andWhere('u.user_lastname LIKE :lastname')
                ->setParameter('lastname', '%' . $lastname . '%');
        }
        
        // If no criteria provided, return all users
        return $queryBuilder
            ->orderBy('u.user_username', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all unique usernames
     * 
     * @return array List of unique usernames
     */
    public function findUniqueUsernames(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.user_username')
            ->orderBy('u.user_username', 'ASC')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all unique first names
     * 
     * @return array List of unique first names
     */
    public function findUniqueFirstnames(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.user_firstname')
            ->where('u.user_firstname IS NOT NULL')
            ->orderBy('u.user_firstname', 'ASC')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all unique last names
     * 
     * @return array List of unique last names
     */
    public function findUniqueLastnames(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.user_lastname')
            ->where('u.user_lastname IS NOT NULL')
            ->orderBy('u.user_lastname', 'ASC')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all search options (usernames, firstnames, lastnames)
     * 
     * @return array Associative array with all options
     */
    public function findAllSearchOptions(): array
    {
        $usernames = array_map(function($item) {
            return $item['user_username'];
        }, $this->findUniqueUsernames());
        
        $firstnames = array_map(function($item) {
            return $item['user_firstname'];
        }, $this->findUniqueFirstnames());
        
        $lastnames = array_map(function($item) {
            return $item['user_lastname'];
        }, $this->findUniqueLastnames());
        
        return [
            'usernames' => $usernames,
            'firstnames' => $firstnames,
            'lastnames' => $lastnames
        ];
    }

    /**
     * Search users by username, email and firstname
     * 
     * @param string|null $username
     * @param string|null $email
     * @param string|null $firstname
     * @return User[] Returns an array of User objects
     */
    public function searchByFilters(?string $username = null, ?string $email = null, ?string $firstname = null): array
    {
        $queryBuilder = $this->createQueryBuilder('u');
        
        // Add conditions based on provided parameters
        if ($username) {
            $queryBuilder
                ->andWhere('u.user_username LIKE :username')
                ->setParameter('username', '%' . $username . '%');
        }
        
        if ($email) {
            $queryBuilder
                ->andWhere('u.user_email LIKE :email')
                ->setParameter('email', '%' . $email . '%');
        }
        
        if ($firstname) {
            $queryBuilder
                ->andWhere('u.user_firstname LIKE :firstname')
                ->setParameter('firstname', '%' . $firstname . '%');
        }
        
        return $queryBuilder
            ->orderBy('u.user_username', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
