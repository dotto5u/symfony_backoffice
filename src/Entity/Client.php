<?php

namespace App\Entity;

use DateTime;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'client.email.already_exists')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'client.firstname.not_blank')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'client.firstname.min_length', maxMessage: 'client.firstname.max_length')]
    #[Assert\Regex(pattern: "/^[a-zA-ZÀ-ÿ -]+$/", message: 'firstname.format')]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'client.lastname.not_blank')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'client.lastname.min_length', maxMessage: 'client.lastname.max_length')]
    #[Assert\Regex(pattern: "/^[a-zA-ZÀ-ÿ -]+$/", message: 'lastname.format')]
    private ?string $lastname = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'client.email.not_blank')]
    #[Assert\Length(min: 5, max: 180, minMessage: 'client.email.min_length', maxMessage: 'client.email.max_length')]
    #[Assert\Email(message: 'client.email.format')]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'client.phone_number.not_blank')]
    #[Assert\Length(min: 7, max: 50, minMessage: 'client.phone_number.min_length', maxMessage: 'client.phone_number.max_length')]
    #[Assert\Regex(pattern: "/^\+?[0-9]+$/", message: 'client.phone_number.format')]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'client.address.not_blank')]
    #[Assert\Length(min: 5, max: 255, minMessage: 'client.address.min_length', maxMessage: 'client.address.max_length')]
    private ?string $address = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTime $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFullname(): string
    {
        $firstname = $this->firstname;
        $lastname = strtoupper($this->lastname);

        return "$firstname $lastname";
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
