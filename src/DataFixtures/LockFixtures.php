<?php

namespace App\DataFixtures;

use App\Entity\Lock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LockFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 20; $i++) {
            $lock = new Lock('lock ' .$i);
            $lock->setData('127.0.0.10');
            $lock->setExpire((new \DateTime('now'))->add(new \DateInterval('P1D')));
            $lock->setValue($i);
            $manager->persist($lock);
        }
        $manager->flush();
    }
}
