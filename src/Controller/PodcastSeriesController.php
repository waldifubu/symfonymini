<?php

namespace App\Controller;

use App\Entity\PodcastSeries;
use App\Form\PodcastSeries1Type;
use App\Form\PodcastSeriesType;
use App\Repository\PodcastSeriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/podcast/series')]
final class PodcastSeriesController extends AbstractController
{
    #[Route(name: 'app_podcast_series_index', methods: ['GET'])]
    public function index(PodcastSeriesRepository $podcastSeriesRepository): Response
    {
        return $this->render('podcast_series/index.html.twig', [
            'podcast_series' => $podcastSeriesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_podcast_series_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $podcastSeries = new PodcastSeries();
        $form = $this->createForm(PodcastSeriesType::class, $podcastSeries);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($podcastSeries);
            $entityManager->flush();

            return $this->redirectToRoute('app_podcast_series_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('podcast_series/new.html.twig', [
            'podcast_series' => $podcastSeries,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_podcast_series_show', methods: ['GET'])]
    public function show(PodcastSeries $podcastSeries): Response
    {
        return $this->render('podcast_series/show.html.twig', [
            'podcast_series' => $podcastSeries,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_podcast_series_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PodcastSeries $podcastSeries, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PodcastSeriesType::class, $podcastSeries);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_podcast_series_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('podcast_series/edit.html.twig', [
            'podcast_series' => $podcastSeries,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_podcast_series_delete', methods: ['POST'])]
    public function delete(Request $request, PodcastSeries $podcastSeries, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$podcastSeries->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($podcastSeries);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_podcast_series_index', [], Response::HTTP_SEE_OTHER);
    }
}
