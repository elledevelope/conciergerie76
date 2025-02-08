<?php
// src/Repository/DrinkingWaterNodeRepository.php
namespace App\Repository;

use App\Entity\DrinkingWaterNode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DrinkingWaterNodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrinkingWaterNode::class);
    }

    // Custom query to find all drinking water nodes within a specific bounding box
    public function findWithinBoundingBox(float $minLat, float $minLon, float $maxLat, float $maxLon)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.lat BETWEEN :minLat AND :maxLat')
            ->andWhere('d.lon BETWEEN :minLon AND :maxLon')
            ->setParameter('minLat', $minLat)
            ->setParameter('minLon', $minLon)
            ->setParameter('maxLat', $maxLat)
            ->setParameter('maxLon', $maxLon)
            ->getQuery()
            ->getResult();
    }

    // You can add more custom methods here as needed (e.g., by name, proximity, etc.)
}
