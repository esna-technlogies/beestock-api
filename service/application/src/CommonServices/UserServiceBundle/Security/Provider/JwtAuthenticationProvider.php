<?php

namespace CommonServices\UserServiceBundle\Security\Provider;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\lib\UserManagerService;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\JwtToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class JwtAuthenticationProvider
 * @package CommonServices\UserServiceBundle\Security\Provider
 */
class JwtAuthenticationProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cachePool;

    /**
     * JwtAuthenticationProvider constructor.
     * @param UserProviderInterface $userProvider
     * @param CacheItemPoolInterface $cachePool
     */
    public function __construct(UserProviderInterface $userProvider, CacheItemPoolInterface $cachePool)
    {
        $this->userProvider = $userProvider;
        $this->cachePool = $cachePool;
    }

    /**
     * @inheritdoc
     */
    public function authenticate(TokenInterface $token)
    {
        /** @var User $user */
        $user = $this->userProvider->loadUserByUsername($token->getUser());

        if ($user) {
            $authenticatedToken = new JwtToken($user->getUuid(), $user->getAccessInfo()->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The JWT authentication failed.');
    }

    /**
     * @inheritdoc
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof JwtToken;
    }
}