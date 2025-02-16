<?php

namespace App\Command;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:client:create',
    description: 'Créé un client'
)]
class CreateClientCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager, private ValidatorInterface $validator) {
        parent::__construct();
    }

    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $client = new Client();

        $client->setFirstname($this->askAndValidate($io, 'firstname', 'Entrez le prénom du client '));
        $client->setLastname($this->askAndValidate($io, 'lastname', 'Entrez le nom du client '));
        $client->setEmail($this->askAndValidate($io, 'email', 'Entrez l\'adresse e-mail du client '));
        $client->setPhoneNumber($this->askAndValidate($io, 'phoneNumber', 'Entrez le numéro de téléphone du client '));
        $client->setAddress($this->askAndValidate($io, 'address', 'Entrez l\'adresse du client '));
        $client->setCreatedAt(new \DateTime());

        $errors = $this->validator->validate($client);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $io->error($error->getMessage());
            }

            return Command::FAILURE;
        }

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $io->success('Client créé avec succès');

        return Command::SUCCESS;
    }

    private function askAndValidate(SymfonyStyle $io, string $property, string $question): string
    {
        do {
            $value = $io->ask($question);

            $errors = $this->validator->validatePropertyValue(Client::class, $property, $value);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $io->error($error->getMessage());
                }
            }
        } while (count($errors) > 0);

        return $value;
    }
}
