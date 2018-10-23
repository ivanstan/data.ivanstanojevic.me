<?php

namespace App\Repository;

use App\Entity\Metar;
use App\Model\PaginationCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MetarRepository extends ServiceEntityRepository
{
    public const SORT_DATE = 'date';

    public static $sort = [self::SORT_DATE];

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Metar::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function latest(string $icao): ?Metar
    {
        return $this->createQueryBuilder('m')
            ->where('m.icao = :icao')
            ->setParameter('icao', $icao)
            ->addOrderBy('m.date', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return PaginationCollection
     */
    public function collection(
        ?string $search,
        string $sort,
        string $sortDir,
        int $pageSize,
        int $offset
    ): PaginationCollection {
        $builder = $this->createQueryBuilder('m');

        if ($search) {
            $builder
                ->where('m.icao = :search')
                ->setParameter('search', $search);
        }

        // sort
        $builder->orderBy('m.'.$sort, $sortDir);

        // get total
        $total = \count($builder->getQuery()->getResult());

        // limit
        $builder->setMaxResults($pageSize);
        $builder->setFirstResult($offset);

        $collection = new PaginationCollection();

        $collection->setCollection($builder->getQuery()->getResult());
        $collection->setTotal($total);

        return $collection;
    }
}
