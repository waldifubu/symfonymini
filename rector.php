<?php
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Symfony\Set\SensiolabsSetList;
//use Rector\Nette\Set\NetteSetList;
use Rector\Config\RectorConfig;

return function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        SetList::CODE_QUALITY
//        NetteSetList::ANNOTATIONS_TO_ATTRIBUTES,
//        SensiolabsSetList::FRAMEWORK_EXTRA_61,
    ]);
};