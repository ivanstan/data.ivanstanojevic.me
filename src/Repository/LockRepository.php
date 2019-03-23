<?php

namespace App\Repository;

use App\Entity\Lock;
use App\EventSubscriber\SecuritySubscriber;
use App\Service\System\DateTimeService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class LockRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Lock::class);
    }

    /**
     * @return Lock[]
     */
    public function getActiveLocks(): array
    {
        $builder = $this
            ->createQueryBuilder('l')
            ->select('l')
            ->andWhere('l.expire > :now AND l.value > :value')
            ->setParameter('now', DateTimeService::getCurrentUTC())
            ->setParameter('value', SecuritySubscriber::BAN_AFTER_ATTEMPTS)
        ;

        return $builder->getQuery()->getResult();
    }

    public function getExpiredLocks(): array
    {
        $builder = $this
            ->createQueryBuilder('l')
            ->select('l')
            ->andWhere('l.expire <= :now')
            ->setParameter('now', DateTimeService::getCurrentUTC())
        ;

        return $builder->getQuery()->getResult();
    }

    public function getLock(string $name, $data = null): ?Lock
    {
        $builder = $this
            ->createQueryBuilder('l')
            ->select('l')
            ->where('l.name = :name')
            ->setParameter('name', $name)
            ->andWhere('l.expire > :now')
            ->setParameter('now', DateTimeService::getCurrentUTC())
        ;

        if ($data) {
            $builder->andWhere('l.data = :data')->setParameter('data', $data);
        }

        $result = $builder->getQuery()->getResult();

        return $result[0] ?? null;
    }
}
