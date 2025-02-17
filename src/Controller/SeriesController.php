<?php

namespace App\Controller;

use App\DBAL\MainCategoryEnum;
use App\DBAL\SubCategoryEnum;
use App\Entity\PodcastSeries;
use App\Entity\Series;
use App\Form\PodcastSeriesType;
use App\Form\SeriesType;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use UnitEnum;

#[Route('/api/series')]
final class SeriesController extends AbstractController
{
    #[Route(name: 'app_series_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
//        $seriesList = $entityManager->getRepository(Series::class)->findAll();


        $query = $entityManager->createQuery(
            'SELECT c FROM App\Entity\\PodcastSeries c'
        );
        $data = $query->getArrayResult();

        /*
        $data = [];

        foreach ($seriesList as $project) {
            $data[] = [
                'id' => $project->getId(),
                'title' => $project->getTitle(),
                'author' => $project->getAuthor(),
            ];
        }
*/
        return $this->json($data);
    }

    #[Route(name: 'app_series_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->request->all();
        $series = new PodcastSeries();

        $data['mainCategory'] = $this->getEnumKeyFromValue($data['mainCategory'], MainCategoryEnum::class);
        $data['subCategory'] = $this->getEnumKeyFromValue($data['subCategory'], SubCategoryEnum::class);
        $form = $this->createForm(PodcastSeriesType::class, $series);
        $form->handleRequest($request);
        $form->submit($data);


        if ($form->isSubmitted() && $form->isValid()) {
            $series->setCreated(new \DateTimeImmutable());
            $entityManager->persist($series);
//            $entityManager->flush();

            return $this->json('Created', Response::HTTP_CREATED);
        }

        return $this->json($form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'app_series_show', methods: ['GET'])]
    public function show(Series $series): Response
    {
        return $this->render('series/show.html.twig', [
            'series' => $series,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_series_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Series $series, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeriesType::class, $series);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('series/edit.html.twig', [
            'series' => $series,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_series_delete', methods: ['POST'])]
    public function delete(Request $request, Series $series, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $series->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($series);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);
    }

    function getEnumKeyFromValue(string $value, string $enumClass): mixed //?UnitEnum
    {
        if (!enum_exists($enumClass)) {
            throw new InvalidArgumentException("$enumClass is not a valid enum.");
        }

        // Get all cases of the enum
        $cases = $enumClass::cases();

        // Iterate through the cases and compare the value
        foreach ($cases as $case) {
            if ($case->name === $value) {
                return $case->value; // Return the key (case name)
                //return $case; // Return the key (case name)
            }
        }

        return null; // Return null if no match is found
    }
}