<?php

namespace App\Repository;

use App\Entity\Lock;
use App\Event\SecuritySubscriber;
use App\Service\DateTimeService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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
    public function findAll(): QueryBuilder
    {
        $builder = $this
            ->createQueryBuilder('l')
            ->select('l as lock')
            ->addSelect('CASE WHEN (l.expire > :now AND l.value > :value) THEN true ELSE false END as active')
            ->setParameter('now', DateTimeService::getCurrentUTC())
            ->setParameter('value', SecuritySubscriber::LOGIN_ATTEMPTS_BAN)
        ;

        $builder->orderBy('active', 'DESC');
        $builder->orderBy('l.expire', 'DESC');

        return $builder;
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

    public function updateLock(string $lockName, string $data): ?Lock
    {
        $interval = new \DateInterval('PT1H');
        $expire = DateTimeService::getCurrentUTC()->add($interval);

        $lock = $this->getLock($lockName, $data);
        if ($lock) {
            $lock->setValue($lock->getValue() + 1);
            $lock->setExpire($expire);
            $this->getEntityManager()->flush();

            return $lock;
        }

        $lock = new Lock($lockName);
        $lock->setData($data);
        $lock->setValue(1);
        $lock->setExpire($expire);

        $this->getEntityManager()->persist($lock);
        $this->getEntityManager()->flush();

        return $lock;
    }
}
