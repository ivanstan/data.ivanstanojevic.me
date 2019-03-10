<?php

namespace App\Repository;

use App\Entity\Lock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class LockRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Lock::class);
    }

    /**
     * @return array[Lock]
     * @throws \Exception
     */
    public function getActiveLocks(): array
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));

        $builder = $this
            ->createQueryBuilder('l')
            ->select('l')
            ->andWhere('l.expire > :now OR l.expire IS NULL')->setParameter('now', $now)
        ;

        return $builder->getQuery()->getResult();
    }

    public function getLock(string $name, $data = null): ?Lock
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));

        $builder = $this
            ->createQueryBuilder('l')
            ->select('l')
            ->where('l.name = :name')->setParameter('name', $name)
            ->andWhere('l.expire > :now')->setParameter('now', $now)
        ;

        if ($data) {
            $builder->andWhere('l.data = :data')->setParameter('data', $data);
        }

        $result = $builder->getQuery()->getResult();

        return $result[0] ?? null;
    }
}