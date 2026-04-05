<?php

namespace App\Entity;

use App\Repository\RadioplayRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RadioplayRepository::class)]
class Radioplay extends File
{
    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
    }


}
