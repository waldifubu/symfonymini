<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Setono\CronExpressionBundle\Doctrine\DBAL\Types\CronExpressionType;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: CronExpressionType::CRON_EXPRESSION_TYPE)]
    private $schedule = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastTime = null;

    public function __construct()
    {
        $this->lastTime = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchedule()
    {
        return $this->schedule;
    }

    public function setSchedule($schedule): static
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getLastTime(): ?\DateTimeInterface
    {
        return $this->lastTime;
    }

    public function setLastTime(\DateTimeInterface $lastTime): static
    {
        $this->lastTime = $lastTime;

        return $this;
    }
}
