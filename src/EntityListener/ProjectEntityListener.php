<?php

namespace App\EntityListener;

use App\Entity\Project;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\Uid\Uuid;

readonly class ProjectEntityListener
{
    public function __construct(
        private SluggerInterface $slugger
    )
    {
    }

    public function preUpdate(Project $project, PreUpdateEventArgs $eventArgs): void
    {
        if ($eventArgs->hasChangedField('name')) {
            $project->setSlug($this->slugMe($eventArgs->getNewValue('name')));
        }
    }

    public function prePersist(Project $project, PrePersistEventArgs $prePersistEventArgs): void
    {
        /** @var Project $project */
        $project->setSlug($this->slugMe($project->getName()));
        $project->setUuid((Uuid::v4())->toRfc4122());
    }

    public function slugMe(string $title): string
    {
        return $this->slugger->slug(mb_strtolower(trim($title)))->toString();
    }
}