<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FileRepository::class)]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'discr', type: 'string', length: 30)]
#[DiscriminatorMap(['cover' => Cover::class, 'radioplay' => Radioplay::class])]
#[ORM\HasLifecycleCallbacks]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Assert\Uuid]
    #[ORM\Column(length: 80, unique: true)]
    protected ?string $uuid = null;

    #[ORM\Column]
    protected ?\DateTime $created = null;

    #[ORM\Column(length: 255)]
    protected ?string $url = null;

    #[ORM\ManyToOne]
    protected ?PodcastEpisode $episode = null;

    #[ORM\Column(length: 50, nullable: true)]
    protected ?string $type = null;

    #[ORM\Column(nullable: true)]
    protected ?int $size = null;

    #[ORM\Column(length: 255)]
    protected ?string $path = null;

    #[ORM\Column(length: 50, nullable: true)]
    protected ?string $storage = null;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function setCreated(?\DateTime $created): void
    {
        $this->created = $created;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getEpisode(): ?PodcastEpisode
    {
        return $this->episode;
    }

    public function setEpisode(?PodcastEpisode $episode): void
    {
        $this->episode = $episode;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): void
    {
        $this->size = $size;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function setStorage(?string $storage): void
    {
        $this->storage = $storage;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->created = new \DateTime();
    }
}
