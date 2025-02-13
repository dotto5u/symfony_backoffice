<?php

namespace App\Service;

class CsvService
{
    public function exportProducts(array $products): string
    {
        $fp = fopen('php://temp', 'w');
        fputcsv($fp, ['name', 'description', 'price']);

        foreach ($products as $product) {
            fputcsv($fp, [
                $product->getName(),
                $product->getDescription(),
                $product->getPrice(),
            ]);
        }

        rewind($fp);
        $csv = stream_get_contents($fp);
        fclose($fp);

        return $csv;
    }
}
