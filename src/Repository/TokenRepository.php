<?php

namespace App\Repository;

use App\Entity\Token;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TokenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Token::class);
    }

    public function getVerificationToken(string $token): ?Token
    {
        $builder =  $this->createQueryBuilder('t')
            ->where('t.token = :token')->setParameter('token', $token)
            ->andWhere('t.type = :type')->setParameter('type', Token::TYPE_VERIFICATION);

        $result = $builder->getQuery()->getResult();

        return $result[0] ?? null;
    }

    public function getRecoveryToken(string $token): ?Token
    {
        $builder =  $this->createQueryBuilder('t')
            ->where('t.token = :token')->setParameter('token', $token)
            ->andWhere('t.type = :type')->setParameter('type', Token::TYPE_RECOVERY);

        $result = $builder->getQuery()->getResult();

        return $result[0] ?? null;
    }
}
