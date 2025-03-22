<?php

namespace App\Entity;

use App\Repository\PodcastEpisodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PodcastEpisodeRepository::class)]
class PodcastEpisode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'episodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PodcastSeries $series = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(length: 545, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $published = null;

    #[ORM\Column(length: 355, nullable: true)]
    private ?string $fileUrl = null;

    #[ORM\Column(nullable: true)]
    private ?int $fileLength = null;

    #[ORM\Column]
    private ?int $episodeNo = null;

    #[ORM\Column]
    private ?bool $explicit = null;

    #[ORM\Column(length: 355, nullable: true)]
    private ?string $coverUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $keywords = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $episodeType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $author = null;

    #[ORM\Column(length: 545, nullable: true)]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeries(): ?PodcastSeries
    {
        return $this->series;
    }

    public function setSeries(?PodcastSeries $series): static
    {
        $this->series = $series;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getFileUrl(): ?string
    {
        return $this->fileUrl;
    }

    public function setFileUrl(?string $fileUrl): static
    {
        $this->fileUrl = $fileUrl;

        return $this;
    }

    public function getFileLength(): ?int
    {
        return $this->fileLength;
    }

    public function setFileLength(?int $fileLength): static
    {
        $this->fileLength = $fileLength;

        return $this;
    }

    public function getEpisodeNo(): ?int
    {
        return $this->episodeNo;
    }

    public function setEpisodeNo(int $episodeNo): static
    {
        $this->episodeNo = $episodeNo;

        return $this;
    }

    public function isExplicit(): ?bool
    {
        return $this->explicit;
    }

    public function setExplicit(bool $explicit): static
    {
        $this->explicit = $explicit;

        return $this;
    }

    public function getCoverUrl(): ?string
    {
        return $this->coverUrl;
    }

    public function setCoverUrl(?string $coverUrl): static
    {
        $this->coverUrl = $coverUrl;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): static
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getEpisodeType(): ?string
    {
        return $this->episodeType;
    }

    public function setEpisodeType(?string $episodeType): static
    {
        $this->episodeType = $episodeType;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
