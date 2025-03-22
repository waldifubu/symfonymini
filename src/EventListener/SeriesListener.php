<?php

namespace App\EventListener;

use App\Entity\PodcastSeries;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Uid\Uuid;

//doctrine.orm.pre_persist
#[AsEntityListener(event: Events::prePersist, method: 'onPrePersist', entity: PodcastSeries::class)]
class SeriesListener
{
    public function onPrePersist(PodcastSeries $series): void
    {
//        $series = $args->getObject();

        if (!$series instanceof PodcastSeries) {
            return;
        }

        $series->setCreated(new DateTimeImmutable());
        $series->setUuid(Uuid::v4());

//        $em = $args->getObjectManager();
    }
}
