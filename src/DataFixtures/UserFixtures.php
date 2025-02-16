<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private const USER_REF_PREFIX = 'user_';

    public function __construct(private UserPasswordHasherInterface $userPasswordHasherInterface) {}

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            [
                'email' => 'admin@example.com',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'adminpass'
            ],
            [
                'email' => 'manager1@example.com',
                'first_name' => 'Manager',
                'last_name' => 'One',
                'roles' => ['ROLE_MANAGER'],
                'password' => 'managerpass1'
            ],
            [
                'email' => 'manager2@example.com',
                'first_name' => 'Manager',
                'last_name' => 'Two',
                'roles' => ['ROLE_MANAGER'],
                'password' => 'managerpass2'
            ],
            [
                'email' => 'user1@example.com',
                'first_name' => 'User',
                'last_name' => 'One',
                'roles' => ['ROLE_USER'],
                'password' => 'userpass1'
            ],
            [
                'email' => 'user2@example.com',
                'first_name' => 'User',
                'last_name' => 'Two',
                'roles' => ['ROLE_USER'],
                'password' => 'userpass2'
            ],
            [
                'email' => 'user3@example.com',
                'first_name' => 'User',
                'last_name' => 'Three',
                'roles' => ['ROLE_USER'],
                'password' => 'userpass3'
            ]
        ];

        foreach ($usersData as $key => $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setFirstname($userData['first_name']);
            $user->setLastname($userData['last_name']);
            $user->setRoles($userData['roles']);
            $user->setPassword(
                $this->userPasswordHasherInterface->hashPassword($user, $userData['password'])
            );
            
            $manager->persist($user);
            $this->addReference(self::USER_REF_PREFIX.($key + 1), $user);
        }

        $manager->flush();
    }
}
