<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
#[ApiResource(
    normalizationContext: [ 'groups' => ['command', 'slot:min', 'store', 'user'] ]
)]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ['command'])]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(groups: ['command'])]
    private ?Slot $slot = null;

    #[ORM\ManyToOne(inversedBy: 'commands')]
    #[Groups(groups: ['command'])]
    private ?Store $store = null;

    #[ORM\ManyToOne(inversedBy: 'commands')]
    #[Groups(groups: ['command'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'command', targetEntity: CommandProduct::class, cascade: ['persist'])]
    #[Groups(groups: ['command:products'])]
    private Collection $commandProducts;

    public function __construct()
    {
        $this->commandProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlot(): ?Slot
    {
        return $this->slot;
    }

    public function setSlot(?Slot $slot): self
    {
        $this->slot = $slot;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $commandProduct->setCommand($this);
        }

        return $this;
    }

    public function removeCommandProduct(CommandProduct $commandProduct): self
    {
        if ($this->commandProducts->removeElement($commandProduct)) {
            // set the owning side to null (unless already changed)
            if ($commandProduct->getCommand() === $this) {
                $commandProduct->setCommand(null);
            }
        }

        return $this;
    }
}
