<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Service\JWTprovider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\TokenProviderInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/messages', name: 'messages_')]
class MessageController extends AbstractController
{
    public function __construct(
        private readonly MessageRepository $messageRepository,
        private readonly EntityManagerInterface $em,
    ) {
    }

    // BREAD controller action pattern

    /**
     * Display list of messages from conversation.
     *
     * @param User|null $user
     */
    #[Route(path: '/{groupConversation}', name: 'browse')]
    public function browse(
        GroupConversation $groupConversation,
        #[CurrentUser] ?User $user
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $messages = $this->messageRepository->findMessageByConversationId($groupConversation->getId());

        /*
         *  $participantIds = array_map(fn (User $user) => $user->getId(), $group->getParticipants()->getValues();
         */

        /** @var Message $m */
        $messages = array_map(static function ($m) use ($user) {
            $m->setMine($m->getUser() === $user);

            return $m;
        }, $messages);

        return $this->render('message/browse.html.twig', [
            'conversation' => $groupConversation,
            'messages' => $messages,
        ]);
    }

    /**
     * @throws \JsonException
     */
    #[Route(path: '/{id}/add', name: 'add', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function add(
        Request              $request,
        HubInterface         $hub,
        GroupConversation    $groupConversation,
        #[CurrentUser] ?User $user,
        JWTprovider          $jwtProvider,
//        Authorization        $authorization
    ): Response {
        //        $this->denyAccessUnlessGranted('ROLE_USER');
        // used with connected user
        // $user = $this->security->getUser();
        if (!$user instanceof UserInterface) {
            $this->addFlash('error', 'User incorrect.');

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
            //            $message->setMine(true);
            $message->setSeen(false);

            $message->setUser($user);
            $groupConversation->addMessage($message);

            //            dd($groupConversation->getUsers());

            try {
                $date = new \DateTime('now');
                $update = new Update(
                    '/messages/'.$groupConversation->getId(),
                    // IRI, the topic being updated, can be any string usually URL
                    json_encode([
                        'conversation' => 'Nouveau message conversation :'.$groupConversation->getName(),
                        'message' => $content,
                        'from' => $user->getUsername(),
                        'to' => $groupConversation->getUsers(),
                        'date' => $date->format('H:i'),
                    ], JSON_THROW_ON_ERROR),
                    // the content of the update, can be anything
                    $groupConversation->getPrivate(),
                    // private
                    'message-'.Uuid::v4(),
                    // mercure id
                    'message'
                );

                // PUBLISHER JWT : doit contenir la liste des conversations dans lesquels il peut publier conf => mercure.publish
                // SUBSCRIBER JWT: doit contenir la liste des conversations dans lesquels il peut recevoir conf => mercure.subcribe

                // Publisher's JWT must contain this topic, a URI template it matches or * in mercure.publish or you'll get a 401
                // Subscriber's JWT must contain this topic, a URI template it matches or * in mercure.subscribe to receive the update

                //                dd($update);
                //                var_dump($update);

                $hub->publish($update);
                $this->em->flush();
            } catch (\Exception $e) {
                // dd($groupConversation);
                throw $e;
            }
        }


        /**
         * https://wiki.alpinelinux.org/wiki/Setting_the_timezone
         */

        $response= $this->redirectToRoute('messages_browse', [
            'groupConversation' => $groupConversation->getId()

        ]);

        $token = $jwtProvider->getJwt();
        //dd($token);

        $response->headers->set(
            'set-cookie',
            'mercureAuthorization='.$token.'; Path=/.well-known/mercure; httponly; SameSite=lax'
        );

        //dd($request->headers->all());


        //'mercureAuthorization='.$token.'; Path=/.well-known/mercure; secure; httponly; SameSite=lax'
//        $authorization->setCookie($request, ['/messages/3']);

        return $response;
    }

    /**
     * Ping mercure.
     *
     * @throws \JsonException
     */
    #[Route(path: '/{id}/ping', name: 'ping')]
    public function ping(Request $request, HubInterface $hub, ?int $id = null): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        //        dd($id);

        $update = new Update(
            '/ping/'.$request->get('id'), // IRI, the topic being updated, can be any string usually URL
            json_encode(['message' => 'pinged !'], JSON_THROW_ON_ERROR), // the content of the update, can be anything
            false, // private
            'ping-'.Uuid::v4(), // mercure id
            'ping'
        );

        $hub->publish($update);
        //        dd($update);
        //        dd($hub);

        return $this->redirectToRoute('messages_browse', ['groupConversation' => $request->get('id')]);
    }
}
