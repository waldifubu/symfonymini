<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\User;
use App\Form\GroupConversationType;
use App\Repository\GroupConversationRepository;
use App\Repository\UserRepository;
use App\Service\CookieGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupConversationController extends AbstractController
{

    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Display list of conversations
     *
     * @param GroupConversationRepository $groupConversationRepository
     * @return Response
     */
    #[Route(path: '/', name: 'conversation_browse')]
    public function browse(GroupConversationRepository $groupConversationRepository, ?CookieGenerator $cookieGenerator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $conversations = $groupConversationRepository->findAll();
        $cookie = $cookieGenerator->generate();
        $response = $this->render('conversation/browse.html.twig', [
            'conversations' => $conversations,
            'jwt' => $cookie->getValue(),
        ]);

        //fix CORS policy
        //$response->headers->set("Access-Control-Allow-Origin", '*');
        //generate cookie for connected user
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * Create new Conversation group
     */
    #[Route(path: '/conversation/add', name: 'conversation_add')]
    public function add(Request                $request,
                        EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        //used with connected user
        /** @var User $user */
        $user = $this->security->getUser();
        if (!($user)) {
            $this->addFlash('error', 'Utilisateur créateur du groupe incorrect.');
            return $this->redirectToRoute('app_login');
        }

        $conversation = new GroupConversation();

        $form = $this->createForm(GroupConversationType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $conversation->setCreated(new \DateTime('now'));
            $conversation->setUpdated(new \DateTime('now'));
            $conversation->setAdmin($user);

            //@TODO: I dont understand why users are not saved with ManyToMany connexion (table user_group_conversation)
            $user->addConversation($conversation);
            if ($conversation->getUsers()) {
                foreach ($conversation->getUsers() as $user) {
                    $user->addConversation($conversation);
                }
            }

            /*
            $errors = $validator->validate($conversation);
            if (count($errors) > 0) {
                return new Response((string)$errors, 400);
            }
            */
            $entityManager->persist($conversation);
            $entityManager->flush();

            return $this->redirectToRoute('conversation_browse');
        }

        return $this->render('conversation/_form/add.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Delete conversation group
     */
    #[Route(path: '/conversation/{id}/delete', name: 'conversation_delete', requirements: ['id' => '\d+'])]
    public function delete(GroupConversation $groupConversation): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

    }
}