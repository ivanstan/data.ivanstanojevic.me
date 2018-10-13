<?php

namespace App\Repository;

use App\Entity\Tle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TleRepository extends ServiceEntityRepository
{
    public const SORT_ID = 'id';
    public const SORT_NAME = 'name';

    public static $sort = [self::SORT_ID, self::SORT_NAME];

    public const PAGE_SIZE = 50;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tle::class);
    }

    /**
     * @return Tle[]|Collection
     */
    public function fetchAllIndexed()
    {
        return $this->createQueryBuilder('tle', 'tle.satelliteId')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Tle[]|Collection
     */
    public function search(string $query = null, $sort = null, $sortDir = null, int $pageSize, int $offset)
    {
        $builder = $this->createQueryBuilder('tle');

        // search
        if ($query) {
            $builder
                ->where(
                    $builder->expr()->orX(
                        $builder->expr()->like('tle.satelliteId', ':query'),
                        $builder->expr()->like('tle.name', ':query')
                    )
                )
                ->setParameter('query', '%'.$query.'%');
        }

        // sort
        $builder->orderBy('tle.'.$sort, $sortDir);

        // limit
        $builder->setMaxResults($pageSize);
        $builder->setFirstResult($offset);

        return $builder->getQuery()->getResult();
    }
}
