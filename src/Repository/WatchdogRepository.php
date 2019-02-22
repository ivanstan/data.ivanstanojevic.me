<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Watchdog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class WatchdogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Watchdog::class);
    }

    public function findAll(): array
    {
        $builder = $this->createQueryBuilder('w');

        $builder->orderBy('w.dateTime', 'DESC');

        return $builder->getQuery()->getResult();
    }

}
