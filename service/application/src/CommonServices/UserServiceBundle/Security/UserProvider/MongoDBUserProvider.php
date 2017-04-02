<?php

namespace CommonServices\UserServiceBundle\Security\UserProvider;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\lib\UserManagerService;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Class MongoDBUserProvider
 * @package CommonServices\UserServiceBundle\Security\User
 */
class MongoDBUserProvider implements UserProviderInterface
{
    /**
     * @var UserManagerService
     */
    private $userManagerService;

    /**
     * WebserviceUser constructor.
     * @param UserManagerService $userManagerService
     */
    public function __construct(UserManagerService $userManagerService)
    {
        $this->userManagerService = $userManagerService;
    }

    /**
     * @inheritdoc
     */
    public function loadUserByUsername($username)
    {
        /** @var User $user */
        $user = $this->userManagerService->getUserByUserName($username);

        if ($user) {

            $uuid = $user->getUuid();

            /** @var AccessInfo $accessInfo */
            $accessInfo = $user->getAccessInfo();

            $password = $accessInfo->getPassword();
            $salt  = $accessInfo->getSalt();
            $roles = $accessInfo->getRoles();
            $email = $user->getEmail();

            return new WebserviceUser($email, $uuid, $password, $salt, $roles);
        }

        throw new UsernameNotFoundException(
            sprintf('User with username "%s" does not exist.', $username)
        );
    }

    /**
     * @inheritdoc
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        return WebserviceUser::class === $class;
    }
}
