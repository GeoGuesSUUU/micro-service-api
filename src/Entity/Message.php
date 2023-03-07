<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource(
    normalizationContext: [ 'groups' => ['message', 'user'] ]
)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ['message'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[Groups(groups: ['message'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[Groups(groups: ['message'])]
    private ?User $seller = null;

    #[ORM\Column(length: 2048)]
    #[Groups(groups: ['message'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(groups: ['message'])]
    private ?\DateTimeInterface $sendDate = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSeller(): ?User
    {
        return $this->seller;
    }

    public function setSeller(?User $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getSendDate(): ?\DateTimeInterface
    {
        return $this->sendDate;
    }

    /**
     * @param \DateTimeInterface|null $sendDate
     */
    public function setSendDate(?\DateTimeInterface $sendDate): void
    {
        $this->sendDate = $sendDate;
    }
}
