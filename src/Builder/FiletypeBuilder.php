<?php

namespace App\Builder;

use App\Entity\Cover;
use App\Entity\File;
use App\Entity\Radioplay;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\UuidV4;

class FiletypeBuilder
{
    private ?string $discriminator = null;
    private ?string $type = null;
    private ?int $size = null;
    private string $uuid;
    private string $url;
    private string $storage;
    private string $path;
    private string $filename;
    private UploadedFile $uploadedFile;

    public static function create(): self
    {
        return new self();
    }

    public function withDiscriminator(?string $discriminator): self
    {
        $this->discriminator = $discriminator;
        return $this;
    }

    public function build(): File
    {
        if (!$this->discriminator) {
            throw new InvalidArgumentException('Discriminator is required');
        }

        $file = match ($this->discriminator) {
            'radioplay' => new Radioplay(),
            'cover' => new Cover(),
            default => throw new InvalidArgumentException(
                sprintf('Unknown discriminator: %s', $this->discriminator)
            ),
        };

        $file->setSize($this->size);
        $file->setPath($this->path);
        $file->setStorage($this->storage);
        $file->setUrl($this->url);
        $file->setUuid($this->uuid);
        $file->setType($this->type);

        return $file;
    }

    public function withUploadedFile(?UploadedFile $uploadedFile): self
    {
        // Auto-set properties from UploadedFile if not already set
        if ($uploadedFile) {
            if (!$this->type) {
                $this->type = mime_content_type($uploadedFile->getPathname());
            }

            if (!$this->size) {
                $this->size = $uploadedFile->getSize();
            }

            $this->uploadedFile = $uploadedFile;
        }
        return $this;
    }

    public function withUUID(UuidV4 $realUUID): static
    {
        $this->uuid = $realUUID->toRfc4122();
        return $this;
    }

    public function withStorage(?string $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    public function withPath(string $uploadDir)
    {
        $this->path = $uploadDir;
        return $this;
    }

    public function withUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function withType(?string $type)
    {
        $this->type = $type;
        return $this;
    }

    public function withSize(?int $size)
    {
        $this->size = $size;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getUploadedFile(): UploadedFile
    {
        return $this->uploadedFile;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function withFilename(string $filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }
}
