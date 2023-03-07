<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StoreRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['store']]
)]
class Store
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ['store'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['store'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['store'])]
    private ?string $address = null;

    #[ORM\Column]
    #[Groups(groups: ['store'])]
    private ?string $zip = null;

    #[ORM\OneToMany(mappedBy: 'store', targetEntity: User::class)]
    #[Groups(groups: ['store:sellers'])]
    private Collection $sellers;

    #[ORM\OneToMany(mappedBy: 'store', targetEntity: StoreProduct::class)]
    #[Groups(groups: ['store:products'])]
    private Collection $storeProducts;

    #[ORM\OneToMany(mappedBy: 'store', targetEntity: Slot::class)]
    private Collection $slots;

    #[ORM\OneToMany(mappedBy: 'store', targetEntity: Command::class)]
    private Collection $commands;

    public function __construct()
    {
        $this->sellers = new ArrayCollection();
        $this->storeProducts = new ArrayCollection();
        $this->slots = new ArrayCollection();
        $this->commands = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSellers(): Collection
    {
        return $this->sellers;
    }

    public function addSeller(User $seller): self
    {
        if (!$this->sellers->contains($seller)) {
            $this->sellers->add($seller);
            $seller->setStore($this);
        }

        return $this;
    }

    public function removeSeller(User $seller): self
    {
        if ($this->sellers->removeElement($seller)) {
            // set the owning side to null (unless already changed)
            if ($seller->getStore() === $this) {
                $seller->setStore(null);
            }
        }

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
     * @return Collection<int, Slot>
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(Slot $slot): self
    {
        if (!$this->slots->contains($slot)) {
            $this->slots->add($slot);
            $slot->setStore($this);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getStore() === $this) {
                $slot->setStore(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Command>
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }

    public function addCommand(Command $command): self
    {
        if (!$this->commands->contains($command)) {
            $this->commands->add($command);
            $command->setStore($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getStore() === $this) {
                $command->setStore(null);
            }
        }

        return $this;
    }
}

