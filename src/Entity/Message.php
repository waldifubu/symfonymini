<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private ?string $content;

    #[ORM\Column(type: 'boolean')]
    private ?bool $seen;

    #[ORM\ManyToOne(targetEntity: GroupConversation::class, cascade: ['persist'], inversedBy: 'messages')]
    private $conversation;

    #[ORM\Column(type: 'datetimetz')]
    private ?\DateTimeInterface $created;

    #[ORM\Column(type: 'datetimetz')]
    private ?\DateTimeInterface $updated;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'messages')]
    private $user;

    private ?bool $mine;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getMine()
    {
        return $this->mine;
    }

    public function setMine($mine): void
    {
        $this->mine = $mine;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSeen(): ?bool
    {
        return $this->seen;
    }

    public function setSeen(bool $seen): self
    {
        $this->seen = $seen;

        return $this;
    }

    public function getConversation(): ?GroupConversation
    {
        return $this->conversation;
    }

    public function setConversation(?GroupConversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

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
}
