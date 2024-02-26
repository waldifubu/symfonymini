<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: '`user`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $username;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $mail;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'array')]
    private array $roles = ['ROLE_USER'];

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'user', orphanRemoval: true, cascade: ['persist'])]
    private PersistentCollection  $messages;

    #[ORM\ManyToMany(targetEntity: GroupConversation::class, inversedBy: 'users', cascade: ['persist'])]
    private PersistentCollection  $conversations;

    #[ORM\OneToMany(targetEntity: GroupConversation::class, mappedBy: 'admin', cascade: ['persist'])]
    private PersistentCollection  $adminGroupConversations;

    #[ORM\Column(type: 'boolean')]
    private ?bool $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $activationToken;


    public function __construct()
    {
        $this->messages                 = new PersistentCollection ();
        $this->conversations            = new PersistentCollection ();
        $this->adminGroupConversations  = new PersistentCollection ();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return string the hashed password for this user
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        // set the owning side to null (unless already changed)
        if ($this->messages->removeElement($message) && $message->getUser() === $this) {
            $message->setUser(null);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(GroupConversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
        }

        return $this;
    }

    public function removeConversation(GroupConversation $conversation): self
    {
        $this->conversations->removeElement($conversation);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAdminGroupConversations(): Collection
    {
        return $this->adminGroupConversations;
    }

    public function addGroupConversation(GroupConversation $groupConversation): self
    {
        if (!$this->adminGroupConversations->contains($groupConversation)) {
            $this->adminGroupConversations[] = $groupConversation;
            $groupConversation->setAdmin($this);
        }

        return $this;
    }

    public function removeGroupConversation(GroupConversation $groupConversation): self
    {
        // set the owning side to null (unless already changed)
        if ($this->adminGroupConversations->removeElement($groupConversation) && $groupConversation->getAdmin() === $this) {
            $groupConversation->setAdmin(null);
        }

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return (int) $this->getId();
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }

    public function setActivationToken(?string $activationToken): self
    {
        $this->activationToken = $activationToken;

        return $this;
    }
}
