<?php

namespace App\Entity;

namespace App\Entity;

use App\Enum\Status;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class BlogPost
{
    #[ORM\Column(enumType: Status::class)]
    private Status $status;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Getter and Setter
    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;
        return $this;
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
}