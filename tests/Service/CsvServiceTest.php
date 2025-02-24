<?php

namespace Tests\Service;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;
use App\Service\CsvService;

class CsvServiceTest extends TestCase
{
    public function testExportAvecListeVide()
    {
        $csvService = new CsvService();
        
        $csv = $csvService->exportProducts([]);
        $expected = "name,description,price\n";

        $this->assertEquals($expected, $csv, "Le CSV généré pour une liste vide doit contenir uniquement l'en-tête");
    }

    public function testExportAvecUnProduit()
    {
        $csvService = new CsvService();

        $product = (new Product())
                ->setName('Produit 1')
                ->setDescription('Description')
                ->setPrice(100);

        $csv = $csvService->exportProducts([$product]);
        $expected = "name,description,price\n\"Produit 1\",Description,100\n";

        $this->assertEquals($expected, $csv, "Le CSV généré pour un produit est incorrect");
    }

    public function testExportAvecPlusieursProduits()
    {
        $csvService = new CsvService();

        $product1 = (new Product())
                ->setName('Produit 1')
                ->setDescription('Description 1')
                ->setPrice(100);

        $product2 = (new Product())
                ->setName('Produit 2')
                ->setDescription('Description 2')
                ->setPrice(200);

        $csv = $csvService->exportProducts([$product1, $product2]);
        $expected = "name,description,price\n\"Produit 1\",\"Description 1\",100\n\"Produit 2\",\"Description 2\",200\n";

        $this->assertEquals($expected, $csv, "Le CSV généré pour plusieurs produits est incorrect");
    }
}
