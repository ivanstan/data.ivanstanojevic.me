<?php

namespace App\Repository;

use App\Entity\Airport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AirportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Airport::class);
    }

    public function getCollection(array $icao)
    {
        return $this->createQueryBuilder('a', 'a.icao')
            ->where('a.icao IN (:icao)')
            ->setParameter('icao', $icao)
            ->orderBy('a.name')
            ->getQuery()
            ->getResult();
    }
}
