<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/api', name: 'api_')]
class ProjectController extends AbstractController
{
    #[Route('/project', name: 'app_index', methods: ['GET'])]
    public function index(EntityManagerInterface $customerEntityManager): Response
    {
        $projects = $customerEntityManager->getRepository(Project::class)->findAll();
        /*
        $data = [];

        foreach ($projects as $project) {
            $data[] = [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'description' => $project->getDescription(),
                'slug' => $project->getSlug(),
            ];
        }
        */

        return $this->json($projects);
    }

    #[Route('/project', name: 'project_name', methods: ['POST'])]
    public function post(EntityManagerInterface $customerEntityManager, Request $request): Response
    {
        $project = new Project();
        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));

        $repo = $customerEntityManager->getRepository(Project::class);
        $repo->add($project, true);

        return $this->json('Created new project successfully with id ' . $project->getId());
    }


    #[Route('/project/{slug}', name: 'project_show', methods: ['GET'])]
    public function get(EntityManagerInterface $customerEntityManager, string $slug): Response
    {
        $project = $this->getProject($customerEntityManager, $slug);

        if (!$project) {
            return $this->json('No project found with title: ' . $slug, 404);
        }
        /*
                $data = [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'description' => $project->getDescription(),
                    'slug' => $project->getSlug(),
                    'uuid' => $project->getUuid(),
                ];
        */
        return $this->json($project);
    }

    #[Route('/project/{slug}', name: 'project_edit', methods: ['PUT', 'PATCH'])]
    public function patch(
        EntityManagerInterface $customerEntityManager,
        Request                $request,
        string                 $slug = null
    ): Response
    {
        $project = $this->getProject($customerEntityManager, $slug);

        if (!$project) {
            return $this->json('No project found', 404);
        }

        try {
            $content = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json('Data invalid', 404);
        }

        if ($project->getName() !== $content?->name || $project->getDescription() !== $content?->description) {
            $project->setName($content?->name);
            $project->setDescription($content?->description);
            $customerEntityManager->flush();
        }

        return $this->json($project);
    }

    #[Route('/project/{slug}', name: 'project_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $customerEntityManager, string $slug = null): Response
    {
        $repo = $customerEntityManager->getRepository(Project::class);
        $project = $this->getProject($customerEntityManager, $slug);

        if (!$project) {
            return $this->json('No project found', 404);
        }

        $id = $project->getId();

        $repo->remove($project, true);
        return $this->json('Deleted a project successfully with id ' . $id);
    }

    /**
     * @param EntityManagerInterface $customerEntityManager
     * @param string $slug
     * @return Project|null
     */
    public function getProject(EntityManagerInterface $customerEntityManager, string $slug): null|Project
    {
        $project = $customerEntityManager->getRepository(Project::class)->findOneBy(['slug' => $slug]);

        if (!$project && Uuid::isValid($slug)) {
            $project = $customerEntityManager->getRepository(Project::class)->findOneBy(['uuid' => $slug]);
        }
        return $project;
    }
}
