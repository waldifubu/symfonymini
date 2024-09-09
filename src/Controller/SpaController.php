<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class SpaController extends AbstractController
{
    #[Route('/spa', name: 'app_spa')]
    public function index2(): Response
    {
        return $this->render('spa/index.html.twig', [
            'controller_name' => 'SpaController',
        ]);
    }



//    #[Route('/', name: 'app_home', requirements: ['reactRouting' => '^(?!api).+'])]
    #[Route('/{reactRouting}', name: 'app_home', requirements: ['reactRouting' => '^(?!api|home).+'], defaults: ['reactRouting' => null])]
    public function index(): Response
    {
        return $this->render('spa/index.html.twig');
    }

    #[Route('/home', name: 'app_home2')]
    public function index3(): Response
    {
        return new Response('Das ist zwei');
    }
}
