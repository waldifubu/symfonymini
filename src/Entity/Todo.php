<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\TodoRepository")]
class Todo
{
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue()]
    #[ORM\Id()]
    private $id;

    #[ORM\Column(type: "string", length: 10, unique: true)]
    private ?string $task;

    #[ORM\Column(type: "string", length: 500)]
    private ?string $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?string
    {
        return $this->task;
    }

    public function setTask(string $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function toArray(): array
    {
        return ['id' => $this->id, 'task' => $this->task, 'description' => $this->description];
    }
}
