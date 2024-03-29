<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommandProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandProductRepository::class)]
class CommandProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandProducts')]
    private ?Command $command = null;

    #[ORM\ManyToOne(inversedBy: 'commandProducts')]
    #[Groups(groups: ['command:products'])]
    private ?Product $product = null;

    #[ORM\Column]
    #[Groups(groups: ['command:products'])]
    #[Assert\PositiveOrZero(message: "The quantity can't be negative")]
    private ?int $quantity = 0;

    #[ORM\Column]
    #[Groups(groups: ['command:products'])]
    #[Assert\PositiveOrZero(message: "The price can't be negative")]
    private ?float $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommand(): ?Command
    {
        return $this->command;
    }

    public function setCommand(?Command $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
