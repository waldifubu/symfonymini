<?php

namespace App\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class FileUploadModel
{
    /**
     * @Assert\NotNull(message="File is required")
     * @Assert\File(
     *     maxSize = "900M",
     *     mimeTypes = {"image/jpeg", "image/png", "audio/mpeg"},
     *     mimeTypesMessage = "Please upload a valid document"
     * )
     */
    private ?UploadedFile $file;

    /**
     * @Assert\NotBlank(message="Path is required")
     */
    private ?string $path;

    /**
     * @Assert\NotBlank(message="Storage is required")
     */
    private ?string $storage;

    /**
     * @Assert\NotBlank(message="Discriminator is required")
     */
    private ?string $discr;

    /**
     * @Assert\NotBlank(message="Filename is required")
     */
    private ?string $fileName;

    // getters and setters
    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function setStorage(string $storage): self
    {
        $this->storage = $storage;
        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function getDiscr(): ?string
    {
        return $this->discr;
    }

    public function setDiscr(string $discr): self
    {
        $this->discr = $discr;
        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }
}
