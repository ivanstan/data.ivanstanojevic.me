<?php

namespace App\Repository;

use App\Entity\Session;
use App\Service\DateTimeService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SessionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Session::class);
    }

    /**
     * @return Session[]
     */
    public function findAll(): array
    {
        $builder = $this->createQueryBuilder('session', 'session.id');

        return $builder->getQuery()->getResult();
    }

    public function get(string $id): Session
    {
        $session = $this->findOneBy(['id' => $id]);

        if ($session === null) {
            $session = new Session();
            $session->setId($id);
        }

        return $session;
    }

    public function remove(string $id): void
    {
        $session = $this->get($id);

        $this->getEntityManager()->remove($session);
    }

    public function purge(): void
    {
        /** @var Session $session */
        foreach ($this->findAll() as $session) {
            $date = clone $session->getDate();

            if ($date->add($session->getLifetime()) >= DateTimeService::getCurrentUTC()) {
                $this->getEntityManager()->remove($session);
            }
        }

        $this->getEntityManager()->flush();
    }
}
