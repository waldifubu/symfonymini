<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Exception;

#[Route("/api/todo", name: "api_todo")]
class TodoController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TodoRepository $todoRepository)
    {
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('todo/spa.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/create', name: 'api_todo_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
//        var_dump($request->getContent());
//        exit;
        $content = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);

//        var_dump($content);

        $form = $this->createForm(TodoType::class);
        $form->submit((array) $content);

        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $propertyName = $error->getOrigin()->getName();
                $errors[$propertyName] = $error->getMessage();
            }
            return $this->json([
                'message' => ['text' => join('\n', $errors), 'level' => 'error'],
            ]);
        }


        $todo = new Todo();

        $todo->setTask($content->task);
        $todo->setDescription($content->description);

        try {
            $this->entityManager->persist($todo);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            return $this->json([
                'message' => ['text' => 'Task has to be unique!', 'level' => 'error'],
            ]);
        }
        return $this->json([
            'todo' => $todo->toArray(),
            'message' => ['text' => 'To-Do has been created!', 'level' => 'success'],
        ]);
    }


    #[Route('/read', name: 'api_todo_read', methods: ['GET'])]
    public function read()
    {
        $todos = $this->todoRepository->findAll();

        $arrayOfTodos = [];
        foreach ($todos as $todo) {
            $arrayOfTodos[] = $todo->toArray();
        }
        return $this->json($arrayOfTodos);
    }


    /**
     * @throws \JsonException
     */
    #[Route('/update/{id}', name: 'api_todo_update', methods: ['PUT'])]
    public function update(Request $request, Todo $todo): JsonResponse
    {
        $content = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);

        $form = $this->createForm(TodoType::class);
        $nonObject = (array) $content;
        unset($nonObject['id']);
        $form->submit($nonObject);

        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true, true) as $error) {
                $propertyName = $error->getOrigin()->getName();
                $errors[$propertyName] = $error->getMessage();
            }
            return $this->json([
                'message' => ['text' => implode('\n', $errors), 'level' => 'error'],
            ]);
        }

        if ($todo->getTask() === $content->task && $todo->getDescription() === $content->description) {
            return $this->json([
                'message' => [
                    'text' => 'There was no change to the To-Do. Neither the task or the description was changed.',
                    'level' => 'error',
                ],
            ]);
        }

        $todo->setTask($content->task);
        $todo->setDescription($content->description);

        try {
            $this->entityManager->flush();
        } catch (Exception $exception) {
            return $this->json([
                'message' => [
                    'text' => 'Could not reach database when attempting to update a To-Do.',
                    'level' => 'error',
                ],
            ]);
        }

        return $this->json([
            'todo' => $todo->toArray(),
            'message' => ['text' => 'To-Do successfully updated!', 'level' => 'success'],
        ]);
    }

    #[Route('/delete/{id}', name: 'api_todo_delete', methods: ['DELETE'])]
    public function delete(Todo $todo): JsonResponse
    {
        try {
            $this->entityManager->remove($todo);
            $this->entityManager->flush();
        } catch (Exception $exception) {
            return $this->json([
                'message' => [
                    'text' => 'Could not reach database when attempting to delete a To-Do.',
                    'level' => 'error',
                ],
            ]);
        }

        return $this->json([
            'message' => ['text' => 'To-Do has successfully been deleted!', 'level' => 'success'],
        ]);
    }
}
