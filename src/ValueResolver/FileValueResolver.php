<?php

namespace App\ValueResolver;


use App\Entity\File;
use App\Repository\FileRepository;
use App\Repository\MediaRepository;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

#[AsTaggedItem(index: 'uuid', priority: 140)]
class FileValueResolver implements ValueResolverInterface
{


    public function __construct(private FileRepository $fileRepository)
    {

    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // Only continue if the argument is of type Media and the route parameter name is 'uuid'
        if ($argument->getType() !== File::class || !$request->attributes->has('uuid')) {
            return [];
        }

        $uuid = $request->attributes->get('uuid');


        // Validate the UUID format (optional)
//        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid)) {
        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException('Invalid UUID format');
        }

        $media = $this->fileRepository->findOneBy(['uuid' => $uuid]);

        if (!$media) {
            throw new NotFoundHttpException('Media not found');
        }

        yield $media;
    }
}
