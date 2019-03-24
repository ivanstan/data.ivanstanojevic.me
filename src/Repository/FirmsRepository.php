<?php

namespace App\Repository;

use App\Entity\Firms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FirmsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Firms::class);
    }

    /**
     * @param \DatePeriod|null $period
     *
     * @return Firms[]
     */
    public function collection(\DateTime $from, \DateTime $to): array
    {
        if ($from > $to) {
            [$to, $from] = [$from, $to];
        }

        $builder = $this->createQueryBuilder('f');

        $builder->where('f.date >= :from')
            ->setParameter('from', $from)
            ->andWhere('f.date <= :to')
            ->setParameter('to', $to);

        $builder->setMaxResults(1000);

        return $builder->getQuery()->getResult();
    }
}
