<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByEmail(string $email): ?User
    {
        $builder = $this->createQueryBuilder('u');

        $builder->where('u.email = :email')->setParameter('email', $email);

        try {
            return $builder->getQuery()->getSingleResult();
        } catch (NoResultException|NonUniqueResultException $exception) {
            return null;
        }
    }

    public function findAll($query = null): QueryBuilder
    {
        $builder = $this->createQueryBuilder('u');

        $builder->orderBy('u.email', 'ASC');

        if ($query !== null) {
            $builder->andWhere('u.email LIKE :query')->setParameter('query', '%'.$query.'%');
        }

        return $builder;
    }
}
