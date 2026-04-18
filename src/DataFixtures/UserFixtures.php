<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $this->hasher->hashPassword($user, 'password123')
        );
        $user->setBalance(0);
        $manager->persist($user);

        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_SUPER_ADMIN']);
        $admin->setPassword(
            $this->hasher->hashPassword($admin, 'admin123')
        );
        $admin->setBalance(1000.5);
        $manager->persist($admin);

        $manager->flush();
    }
}
