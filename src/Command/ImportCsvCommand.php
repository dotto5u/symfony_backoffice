<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import:csv',
    description: 'Importe des produits à partir d\'un fichier CSV situé dans le dossier public/csv/',
)]
class ImportCsvCommand extends Command
{
    private const CSV_DIR = '/public/csv/';

    public function __construct(private EntityManagerInterface $entityManager, private KernelInterface $kernel)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('filename', InputArgument::OPTIONAL, 'Nom du fichier CSV à importer', 'products.csv');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {   
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');
        $filePath = $this->kernel->getProjectDir().self::CSV_DIR.$filename;

        if (!file_exists($filePath)) {
            $io->error(sprintf('Le fichier "%s" n\'existe pas', $filePath));

            return Command::FAILURE;
        }

        $fp = fopen($filePath, 'r');

        if ($fp === false) {
            $io->error(sprintf('Impossible d\'ouvrir le fichier "%s"', $filePath));

            return Command::FAILURE;
        }

        $header = fgetcsv($fp);

        if ($header === false) {
            $io->error('Le fichier CSV est vide ou invalide');
            fclose($fp);

            return Command::FAILURE;
        }

        $expectedHeader = ['name', 'description', 'price'];

        if ($header !== $expectedHeader) {
            $io->error('L\'en-tête du fichier CSV doit être : name, description, price');
            fclose($fp);

            return Command::FAILURE;
        }

        $line = 1;
        $count = 0;

        while (($data = fgetcsv($fp)) !== false) {
            $line++;

            if (count($data) < 3) {
                $io->error(sprintf('Données invalides sur la ligne %d', $line));

                continue;
            }

            [$name, $description, $price] = $data;
            
            if (!is_numeric($price)) {
                $io->error(sprintf('Le prix "%s" n\'est pas valide sur la ligne %d', $price, $line));

                continue;
            }

            $product = new Product();
            $product->setName($name);
            $product->setDescription($description);
            $product->setPrice($price);

            $this->entityManager->persist($product);
            $count++;
        }

        $this->entityManager->flush();
        fclose($fp);

        if ($count === 0) {
            $io->info('Aucun produit n\'a été importé');
        } else {
            $io->success(sprintf('%d produits importés avec succès', $count));
        }

        return Command::SUCCESS;
    }
}
