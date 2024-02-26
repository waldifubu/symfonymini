<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\CookieGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Uid\Uuid;


#[Route(path: '/messages', name: 'messages_')]
class MessageController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(private MessageRepository      $messageRepository,
                                UserRepository                 $userRepository,
                                private EntityManagerInterface $em,
                                private Security               $security)
    {
        $this->userRepository = $userRepository;
    }

    //BREAD controller action pattern

    /**
     * Display list of messages from conversation
     *
     * @param GroupConversation $groupConversation
     * @return Response
     */
    #[Route(path: '/{groupConversation}', name: 'browse')]
    public function browse(GroupConversation    $groupConversation,
                           #[CurrentUser] ?User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $messages = $this->messageRepository->findMessageByConversationId($groupConversation->getId());

        /** @var Message $m */
        $messages = array_map(function ($m) use ($user) {
            $m->setMine($m->getUser() == $user);
            return $m;
        }, $messages);

        return $this->render('message/browse.html.twig', [
            'conversation' => $groupConversation,
            'messages' => $messages,
        ]);
    }

    /**
     * Create new message
     */
    #[Route(path: '/{id}/add', name: 'add', requirements: ['id' => '\d+'])]
    public function add(Request              $request,
                        HubInterface         $hub,
                        GroupConversation    $groupConversation,
                        #[CurrentUser] ?User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        //used with connected user
        //$user = $this->security->getUser();
        if (!$user instanceof UserInterface) {
            $this->addFlash('error', 'Utilisateur incorrect.');
            return $this->redirectToRoute('app_login');
        }

        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        $content = $request->get('message-box', null);

        if ($content) {
            $message->setCreated(new \DateTime('now'));
            $message->setUpdated(new \DateTime('now'));
            $message->setContent($content);
            $message->setMine(true);
            $message->setSeen(false);

            $message->setUser($user);
            $groupConversation->addMessage($message);

            try {
                $date = new \DateTime('now');
                $update = new Update(
                    '/messages/' . $groupConversation->getId(), //IRI, the topic being updated, can be any string usually URL
                    json_encode([
                        'conversation' => 'Nouveau message conversation :' . $groupConversation->getName(),
                        'message' => $content,
                        'from' => $user->getUsername(),
                        'to' => $groupConversation->getUsers(),
                        'date' => $date->format('H:i'),
                    ]), //the content of the update, can be anything
                    $groupConversation->getPrivate(), //private
                    'message-' . Uuid::v4(),//mercure id
                    'message'
                );

                //PUBLISHER JWT : doit contenir la liste des conversations dans lesquels il peut publier conf => mercure.publish
                //SUBSCRIBER JWT: doit contenir la liste des conversations dans lesquels il peut recevoir conf => mercure.subcribe

                $hub->publish($update);
                $this->em->flush();
            } catch (\Exception $e) {
                //dd($groupConversation);
                throw $e;
            }
        }

        return $this->redirectToRoute('messages_browse', ['groupConversation' => $groupConversation->getId()]);
    }

    /**
     * Ping mercure
     */
    #[Route(path: '/{id}/ping', name: 'ping')]
    public function ping(Request $request, HubInterface $hub)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $update = new Update(
            '/ping/' . $request->get('id'), //IRI, the topic being updated, can be any string usually URL
            json_encode(['message' => 'pinged !']), //the content of the update, can be anything
            false, //private
            'ping-' . Uuid::v4(), //mercure id
            'ping'
        );

        $hub->publish($update);

        return $this->redirectToRoute('messages_browse', ['groupConversation' => $request->get('id')]);
    }
}