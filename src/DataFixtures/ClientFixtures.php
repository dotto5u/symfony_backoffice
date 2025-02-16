<?php

namespace App\DataFixtures;

use App\Entity\Client;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    private const CLIENT_REF_PREFIX = 'client_';

    public function load(ObjectManager $manager): void
    {
        $clientsData = [
            [
                'first_name' => 'Alice',
                'last_name' => 'Dupont',
                'email' => 'client1@example.com',
                'phone_number' => '+33123456789',
                'address' => '10 rue de la Paix, Paris'
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Martin',
                'email' => 'client2@example.com',
                'phone_number' => '+33123456780',
                'address' => '20 avenue des Champs-Élysées, Paris'
            ],
            [
                'first_name' => 'Charlie',
                'last_name' => 'Durand',
                'email' => 'client3@example.com',
                'phone_number' => '+33123456781',
                'address' => '30 boulevard Saint-Germain, Paris'
            ]
        ];

        foreach ($clientsData as $key => $clientData) {
            $client = new Client();
            $client->setFirstname($clientData['first_name']);
            $client->setLastname($clientData['last_name']);
            $client->setEmail($clientData['email']);
            $client->setPhoneNumber($clientData['phone_number']);
            $client->setAddress($clientData['address']);
            $client->setCreatedAt(new DateTime());

            $manager->persist($client);
            $this->addReference(self::CLIENT_REF_PREFIX.($key + 1), $client);
        }

        $manager->flush();
    }
}
