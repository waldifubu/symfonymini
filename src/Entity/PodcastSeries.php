<?php

namespace App\Entity;

use App\DBAL\MainCategoryEnum;
use App\DBAL\SubCategoryEnum;
use App\Repository\PodcastSeriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PodcastSeriesRepository::class)]
class PodcastSeries
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null It’s important to have a clear, concise name for your podcast. Make your title specific. A show titled Our Community Bulletin is too vague to attract many subscribers, no matter how compelling the content.
     *
     * Pay close attention to the title as Apple Podcasts uses this field for search.
     *
     * If you include a long list of keywords in an attempt to game podcast search, your show may be removed from the Apple directory.
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var string|null Where description is text containing one or more sentences describing your podcast to potential listeners. The maximum amount of text allowed for this tag is 4000 bytes.
     */
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column]
    private ?bool $locked = null;

    #[ORM\Column]
    private ?bool $explicit = null;

    #[ORM\Column(length: 255)]
    private ?string $copyright = null;

    /**
     * @var string|null
     * Because Apple Podcasts is available in territories around the world, it is critical to specify the language of a podcast. Apple Podcasts only supports values from the ISO 639 list (two-letter language codes, with some possible modifiers, such as "fr-ca").
     *
     * Invalid language codes will cause your feed to fail Apple validation.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $language = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $created = null;

    /**
     * @var string|null <itunes:image>
     * Artwork must be a minimum size of 1400 x 1400 pixels and a maximum size of 3000 x 3000 pixels, in JPEG or PNG format, 72 dpi, with appropriate file extensions (.jpg, .png), and in the RGB colorspace. Confirm your art does not contain an Alpha Channel. These requirements are different from the standard RSS image tag specifications.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cover = null;

    /**
     * @var Collection<int, PodcastEpisode>
     */
    #[ORM\OneToMany(targetEntity: PodcastEpisode::class, mappedBy: 'series', fetch: 'LAZY')]
    private Collection $episodes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $owner = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $keywords = null;

    #[ORM\Column(nullable: true)]
    private ?bool $blocked = null;

    #[ORM\Column(nullable: true)]
    private ?bool $complete = null;

    #[ORM\Column(enumType: MainCategoryEnum::class, nullable: true)]
    private ?MainCategoryEnum $mainCategory = null;

    #[ORM\Column(nullable: true, enumType: SubCategoryEnum::class)]
    private ?SubCategoryEnum $subCategory = null;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function isLocked(): ?bool
    {
        return $this->locked;
    }

    public function setLocked(bool $locked): static
    {
        $this->locked = $locked;

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

    public function getCopyright(): ?string
    {
        return $this->copyright;
    }

    public function setCopyright(string $copyright): static
    {
        $this->copyright = $copyright;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): static
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return Collection<int, PodcastEpisode>
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(PodcastEpisode $episode): static
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes->add($episode);
            $episode->setSeries($this);
        }

        return $this;
    }

    public function removeEpisode(PodcastEpisode $episode): static
    {
        if ($this->episodes->removeElement($episode)) {
            // set the owning side to null (unless already changed)
            if ($episode->getSeries() === $this) {
                $episode->setSeries(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(?string $owner): static
    {
        $this->owner = $owner;

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

    public function isBlocked(): ?bool
    {
        return $this->blocked;
    }

    public function setBlocked(?bool $blocked): static
    {
        $this->blocked = $blocked;

        return $this;
    }

    public function isComplete(): ?bool
    {
        return $this->complete;
    }

    public function setComplete(?bool $complete): static
    {
        $this->complete = $complete;

        return $this;
    }

    public function getMainCategory(): ?MainCategoryEnum
    {
        return $this->mainCategory;
    }

    public function setMainCategory(MainCategoryEnum $mainCategory): static
    {
        $this->mainCategory = $mainCategory;

        return $this;
    }

    public function getSubCategory(): ?SubCategoryEnum
    {
        return $this->subCategory;
    }

    public function setSubCategory(?SubCategoryEnum $subCategory): static
    {
        $this->subCategory = $subCategory;

        return $this;
    }
}
