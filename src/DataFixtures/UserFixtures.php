<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';

    private const PASSWORD = 'test123';

    public function load(ObjectManager $manager): void
    {
        // Add admin user
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setRoles([User::ROLE_ADMIN]);
        $user->setActive(true);
        $user->setVerified(true);
        $user->setPassword(self::PASSWORD);
        $manager->persist($user);
        $this->addReference(self::ADMIN_USER_REFERENCE, $user);
        for ($i = 1; $i < 10; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@example.com");
            $user->setRoles([User::ROLE_USER]);
            $user->setActive($i > 6);
            $user->setVerified($i < 4 || $i > 8);
            $user->setPassword(self::PASSWORD);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
