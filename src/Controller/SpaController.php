<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SpaController extends AbstractController
{
//    #[Route('/', name: 'app_home', requirements: ['reactRouting' => '^(?!api).+'])]
    #[Route('/{reactRouting}', name: 'app_home', requirements: ['reactRouting' => '^(?!api|home).+'], defaults: ['reactRouting' => null])]
    public function index(): Response
    {
        return $this->render('project/spa.html.twig');
    }

    #[Route('/home', name: 'app_home2')]
    public function index2(): Response
    {
        return new Response('Das ist zwei');
    }
}
