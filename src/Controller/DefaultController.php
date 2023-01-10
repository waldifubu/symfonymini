<?php

namespace App\Controller;

use App\Message\SmsNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/message', name: 'app_default')]
    public function index(MessageBusInterface $bus): JsonResponse
    {
        foreach (range(0, 999999) as $key ) {
            $bus->dispatch(new SmsNotification('Look! I created a message! Index: '.$key));
        }

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DefaultController.php',
        ]);
    }
}
