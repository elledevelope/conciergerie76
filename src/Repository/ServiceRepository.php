<?php

namespace App\Repository;

use App\Entity\Service;
use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

        /**
     * @return Service[] Returns an array of Service objects
     */
    public function findAllPlaces(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC') // Order by name alphabetically
            ->getQuery()
            ->getResult();
    }
    
    // public function findByType(string $type): array
    // {
    //     return $this->createQueryBuilder('s')
    //         ->andWhere('s.type = :type')
    //         ->setParameter('type', $type)
    //         ->getQuery()
    //         ->getResult();
    // }
    
//     public function searchByName(string $query): array
// {
//     return $this->createQueryBuilder('s')
//         ->andWhere('s.name LIKE :query')
//         ->setParameter('query', '%' . $query . '%')
//         ->getQuery()
//         ->getResult();
// }


    //    /**
    //     * @return Service[] Returns an array of Service objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Service
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
