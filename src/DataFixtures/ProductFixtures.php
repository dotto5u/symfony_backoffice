<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Product;

class ProductFixtures extends Fixture
{
    private const PRODUCT_REF_PREFIX = 'product_';

    public function load(ObjectManager $manager): void
    {
        $productsData = [
            [
                'name' => 'iPhone 15 Pro',
                'price' => 1199.99,
                'description' => 'Le dernier smartphone d\'Apple avec puce A17 Bionic et appareil photo avancé.',
            ],
            [
                'name' => 'Samsung Galaxy S23 Ultra',
                'price' => 1399.99,
                'description' => 'Smartphone premium de Samsung avec stylet S-Pen et zoom 100x.',
            ],
            [
                'name' => 'MacBook Pro 16 M2',
                'price' => 2499.99,
                'description' => 'Ordinateur portable puissant avec écran Retina et puce Apple M2 Pro.',
            ],
            [
                'name' => 'Dell XPS 13',
                'price' => 1299.99,
                'description' => 'Ultrabook compact avec écran InfinityEdge et processeur Intel Core i7.',
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'price' => 399.99,
                'description' => 'Casque sans fil avec réduction de bruit active et autonomie de 30 heures.',
            ],
            [
                'name' => 'Logitech MX Master 3S',
                'price' => 99.99,
                'description' => 'Souris ergonomique avec molette MagSpeed et connectivité Bluetooth.',
            ],
            [
                'name' => 'iPad Pro 12.9',
                'price' => 1099.99,
                'description' => 'Tablette Apple haut de gamme avec écran Liquid Retina XDR.',
            ],
            [
                'name' => 'Asus ROG Zephyrus G14',
                'price' => 1899.99,
                'description' => 'PC portable gamer performant avec écran 165Hz et carte graphique RTX 4060.',
            ]
        ];

        foreach ($productsData as $key => $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setDescription($productData['description']);

            $manager->persist($product);
            $this->addReference(self::PRODUCT_REF_PREFIX.($key + 1), $product);
        }

        $manager->flush();
    }
}
