<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'product.name.not_blank')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'product.name.min_length', maxMessage: 'product.name.max_length')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'product.description.not_blank')]
    #[Assert\Length(min: 5, max: 255, minMessage: 'product.description.min_length', maxMessage: 'product.description.max_length')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    #[Assert\NotNull(message: 'product.price.not_null')]
    #[Assert\Positive(message: 'product.price.positive')]
    #[Assert\Type(type: 'numeric', message: 'product.price.numeric')]
    #[Assert\LessThanOrEqual(value: 25000, message: 'product.price.less_than_or_equal')]
    private ?string $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;
        return $this;
    }
}
