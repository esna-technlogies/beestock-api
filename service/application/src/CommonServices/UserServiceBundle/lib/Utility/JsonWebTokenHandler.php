<?php

namespace CommonServices\UserServiceBundle\lib\Utility;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Namshi\JOSE\SimpleJWS;

/**
 * Class JsonWebTokenHandler
 * @package CommonServices\UserServiceBundle\lib\Utility
 *
 * The class is deprecated an is no longer in use  |  Lexik JWT bundle is used instead
 */
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
        $sslPrivateKeyFile = $this->serviceContainer->getParameter('ssl_private_key');
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

        $privateKey = openssl_pkey_get_private(file_get_contents($sslPrivateKeyFile), $sslKeyPassPhrase);

        $jsonWebToken->sign($privateKey);

        return $jsonWebToken->getTokenString();
    }

    /**
     * @param string $token
     * @throws InvalidArgumentException
     * @return array
     */
    public function decodeJsonWebToken(string $token) : array
    {

        $sslPublicKeyFile = $this->serviceContainer->getParameter('ssl_public_key');

        $jsonWebToken  = SimpleJWS::load($token);

        $publicKey = openssl_pkey_get_public(file_get_contents($sslPublicKeyFile));

        if ($jsonWebToken->verify($publicKey, 'RS256')) {

            $payload = $jsonWebToken->getPayload();

            //$userService = $this->serviceContainer->get('user_service.core');

            return $payload;
        }

        throw new InvalidArgumentException( 'Invalid json web token', 403);
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isValidJsonWebToken(string $token) : boolean
    {
        $sslPublicKeyFile = $this->serviceContainer->getParameter('ssl_public_key');

        $jsonWebToken  = SimpleJWS::load($token);

        $publicKey = openssl_pkey_get_public(file_get_contents($sslPublicKeyFile));

        return $jsonWebToken->verify($publicKey);
    }
}