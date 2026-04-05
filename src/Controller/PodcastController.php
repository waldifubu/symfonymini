<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PodcastController extends AbstractController
{
    #[Route('/podcast')]
    public function index(): Response
    {
        //return $this->render('podcast/index.html.twig');
        return $this->render('podcast_series/index.html.twig', [
            'podcast_series' => [],
        ]);
    }
}
