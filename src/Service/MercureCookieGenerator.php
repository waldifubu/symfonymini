<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Mercure\Jwt\StaticTokenProvider;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class MercureCookieGenerator
{
    public function __construct(private string $mercureJwtSecret) {}

    public function create(string $username): Cookie
    {
        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($this->mercureJwtSecret)
        );

        $subscribe = [];
        $subscribe[] = '/messages/1';
        $subscribe[] = '/messages/2';
        $subscribe[] = '/messages/3';
        $subscribe[] = '/messages/3/add';
        $now   = new \DateTimeImmutable();
        $token = $config->builder()
            ->issuedAt($now)
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('mercure', [
                'subscribe' => $subscribe, // Only allow chat topic
                'publish' => []          // No publish from browser
            ])
            ->getToken($config->signer(), $config->signingKey());

        return Cookie::create(
            'mercureAuthorization',
            $token->toString(),
            (time() + 3600),
            '/',
            null,
            false,  // secure (true in prod)
            true,  // HttpOnly
            false,
            'Strict'
        );
    }
}
