<?php

namespace App\Controller;

use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @throws JsonException
     */
    #[Route('/publish', name: 'publish')]
    public function publish(HubInterface $hub): JsonResponse
    {
        $update = new Update(
            '/test',
            json_encode(['update' => 'New update received at ' . date('h:i:sa')], JSON_THROW_ON_ERROR)
        );

        $hub->publish($update);

        return $this->json(['message' => 'Update published']);
    }

    #[Route('/info', name: 'info_page')]
    public function info(): true
    {
        return phpinfo();
    }
}
