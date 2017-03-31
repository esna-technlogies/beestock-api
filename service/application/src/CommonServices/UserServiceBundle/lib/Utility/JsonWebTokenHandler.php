<?php

namespace CommonServices\UserServiceBundle\lib\Utility;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Namshi\JOSE\SimpleJWS;

class JsonWebTokenHandler
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * ItemService constructor.
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function getJsonWebToken(User $user) : string
    {
        $sslPrivateKey = $this->serviceContainer->getParameter('ssl_private_key');
        $sslKeyPassPhrase = $this->serviceContainer->getParameter('ssl_key_pass_phrase');

        $jsonWebToken  = new SimpleJWS(['alg' => 'RS256']);

        $jsonWebToken->setPayload(
            [
                'uuid'      => $user->getUuid(),
                'username'  => $user->getAccessInfo()->getUsername(),
                'roles'     => $user->getAccessInfo()->getRoles(),
                'exp'       => time() + $this->serviceContainer->getParameter('jwt_token_ttl'),
            ]
        );

        $privateKey = openssl_pkey_get_private(file_get_contents($sslPrivateKey), $sslKeyPassPhrase);

        $jsonWebToken->sign($privateKey);

        return $jsonWebToken->getTokenString();
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function validateJsonWebToken(string $token) : boolean
    {
        $sslPublicKey = $this->serviceContainer->getParameter('ssl_public_key');

        $jsonWebToken  = SimpleJWS::load($token);

        $publicKey = openssl_pkey_get_public(file_get_contents($sslPublicKey));

        return $jsonWebToken->verify($publicKey);
    }
}