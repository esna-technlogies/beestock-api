<?php

namespace CommonServices\UserServiceBundle\Security\User;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\lib\UserService;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class MongoDBUserProvider implements UserProviderInterface
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function loadUserByUsername($username)
    {
        /** @var User $user */
        $user = $this->userService->getUser($username);

        if ($user) {
            /** @var AccessInfo $accessInfo */
            $accessInfo = $user->getAccessInfo();

            $password = $accessInfo->getPassword();
            $salt  = $accessInfo->getSalt();
            $roles = $accessInfo->getRoles();

            return new WebserviceUser($username, $password, $salt, $roles);
        }

        throw new UsernameNotFoundException(
            sprintf('User with account "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return WebserviceUser::class === $class;
    }
}
