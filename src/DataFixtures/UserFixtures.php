<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';

    private const PASSWORD = 'test123';

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // Add admin user
        $user = new User();
        $user->setEmail('admin1@example.com');
        $user->setRoles([User::ROLE_ADMIN]);
        $user->setActive(true);
        $user->setVerified(true);

        $passwordHash = $this->encoder->encodePassword($user, self::PASSWORD);
        $user->setPassword($passwordHash);

        $manager->persist($user);

        $this->addReference(self::ADMIN_USER_REFERENCE, $user);

        for ($i = 2; $i < 11; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@example.com");
            $user->setRoles([User::ROLE_USER]);
            $user->setActive($i > 6);
            $user->setVerified($i < 4 || $i > 8);
            $user->setPassword($passwordHash);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
