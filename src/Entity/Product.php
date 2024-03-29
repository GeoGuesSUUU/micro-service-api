<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    normalizationContext: [ 'groups' => ['product'] ]
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ['product'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['product'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: StoreProduct::class)]
    private Collection $storeProducts;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: CommandProduct::class)]
    private Collection $commandProducts;

    public function __construct()
    {
        $this->storeProducts = new ArrayCollection();
        $this->commandProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, StoreProduct>
     */
    public function getStoreProducts(): Collection
    {
        return $this->storeProducts;
    }

    public function addStoreProduct(StoreProduct $storeProduct): self
    {
        if (!$this->storeProducts->contains($storeProduct)) {
            $this->storeProducts->add($storeProduct);
            $storeProduct->setProduct($this);
        }

        return $this;
    }

    public function removeStoreProduct(StoreProduct $storeProduct): self
    {
        if ($this->storeProducts->removeElement($storeProduct)) {
            // set the owning side to null (unless already changed)
            if ($storeProduct->getProduct() === $this) {
                $storeProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandProduct>
     */
    public function getCommandProducts(): Collection
    {
        return $this->commandProducts;
    }

    public function addCommandProduct(CommandProduct $commandProduct): self
    {
        if (!$this->commandProducts->contains($commandProduct)) {
            $this->commandProducts->add($commandProduct);
            $commandProduct->setProduct($this);
        }

        return $this;
    }

    public function removeCommandProduct(CommandProduct $commandProduct): self
    {
        if ($this->commandProducts->removeElement($commandProduct)) {
            // set the owning side to null (unless already changed)
            if ($commandProduct->getProduct() === $this) {
                $commandProduct->setProduct(null);
            }
        }

        return $this;
    }
}
