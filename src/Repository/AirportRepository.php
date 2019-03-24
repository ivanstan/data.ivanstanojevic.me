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

    public function getCollection(?array $icao)
    {
        $builder = $this->createQueryBuilder('a', 'a.icao')->orderBy('a.name');

        if ($icao !== null) {
            $builder->where('a.icao IN (:icao)')->setParameter('icao', $icao);
        }

        return $builder->getQuery()->getResult();
    }
}
