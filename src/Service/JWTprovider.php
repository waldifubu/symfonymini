<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mercure\Jwt\TokenProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class JWTprovider implements TokenProviderInterface
{

    /**
     * @var string
     */
    private string $secret;

    private TokenStorageInterface $tokenStorage;

    private EntityManagerInterface $em;

    /**
     * @var Security
     */
    private Security $security;

    public function __construct(string                 $secret,
                                TokenStorageInterface  $tokenStorage,
                                EntityManagerInterface $em,
                                Security               $security)
    {
        $this->secret = $secret;
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
        $this->security = $security;
    }

    public function getJwt(): string
    {
        $user = $this->security->getUser();

        if ($user instanceof UserInterface) {
            $conversations = $user->getConversations()->getValues();
            //save all sub/pub conversations
            $subscribe = [];

            foreach ($conversations as $conversation) {
                $subscribe[] = '/messages/' . $conversation->getId();
            }

            $subscribe[] = '/ping/{id}';
            $subscribe[] = '/ping/1';
            $subscribe[] = '/ping/2';
            $subscribe[] = '/ping/3';

            $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($this->secret));
            $token = $config->builder()
                ->withClaim('mercure', [
                    'subscribe' => $subscribe,
                    'publish' => $subscribe
                ])
                // Builds a new token
                ->getToken($config->signer(), $config->signingKey());

//            dd($config);
//            dd( $token->toString());
            return $token->toString();
        }
        return "";
    }
}