<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:import:csv',
    description: 'Importe des produits à partir d\'un fichier CSV situé dans le dossier public/csv/'
)]
class ImportCsvCommand extends Command
{
    private const CSV_DIR = '/public/csv/';

    public function __construct(private EntityManagerInterface $entityManager,private ValidatorInterface $validator) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('filename', InputArgument::OPTIONAL, 'Nom du fichier CSV à importer');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');

        if (!$filename) {
            $filename = $io->ask('Entrez le nom du fichier CSV à importer', 'products.csv');
        }

        $filePath = $this->getFilePath($filename);

        if (!file_exists($filePath)) {
            $io->error(sprintf('Le fichier "%s" n\'existe pas', $filePath));

            return Command::FAILURE;
        }

        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            $io->error(sprintf('Impossible d\'ouvrir le fichier "%s"', $filePath));

            return Command::FAILURE;
        }

        $header = fgetcsv($handle);
        $expectedHeader = ['name', 'description', 'price'];

        if ($header === false || $header !== $expectedHeader) {
            $io->error('Le fichier CSV doit avoir l\'en-tête suivante : name, description, price');
            fclose($handle);

            return Command::FAILURE;
        }

        $productCount = 0;
        $line = 1;
        $errorCount = 0;

        while (($data = fgetcsv($handle)) !== false) {
            $line++;

            if (count($data) < 3) {
                $io->warning(sprintf('Ligne %d ignorée : nombre de colonnes incorrect', $line));
                $errorCount++;

                continue;
            }

            [$name, $description, $price] = $data;

            $product = (new Product())
                ->setName($name)
                ->setDescription($description)
                ->setPrice($price);

            $errors = $this->validator->validate($product);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $errorCount++;
                    $io->error(sprintf('Ligne %d : %s', $line, $error->getMessage()));
                }

                continue;
            }

            $this->entityManager->persist($product);
            $productCount++;
        }

        fclose($handle);

        if ($productCount > 0) {
            $this->entityManager->flush();
            $io->success(sprintf('%d produit(s) importé(s) avec succès', $productCount));
        } else {
            $io->warning('Aucun produit valide n\'a été importé');
        }

        if ($errorCount > 0) {
            $io->note(sprintf('%d erreur(s) rencontrée(s) lors de l\'importation', $errorCount));
        }

        return Command::SUCCESS;
    }

    private function getFilePath(string $filename): string
    {
        return sprintf('%s%s%s', $this->getProjectDir(), self::CSV_DIR, $filename);
    }

    private function getProjectDir(): string
    {
        return dirname(__DIR__, 2);
    }
}
