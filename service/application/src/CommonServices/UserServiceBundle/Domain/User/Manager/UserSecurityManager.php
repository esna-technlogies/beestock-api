<?php

namespace CommonServices\UserServiceBundle\Domain\User\Manager;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\UserPasswordRetrievalRequestedEvent;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserSecurityManager
 * @package CommonServices\UserServiceBundle\Domain\User\Manager
 */
class UserSecurityManager
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(User $user, UserRepository $userRepository, ContainerInterface $container)
    {
        $this->user = $user;
        $this->userRepository = $userRepository;
        $this->container = $container;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function issueForgotPasswordRequest()
    {
        $user = $this->user;

        $lastTimePasswordChangeRequest = $user->getAccessInfo()->getLastPasswordRetrievalRequest();

        // current time - 10800 (seconds in 3 hours)
        if ($lastTimePasswordChangeRequest >= (time() - 10800)) {
            throw new InvalidArgumentException("Changing the password can't happen before 3 hours from last change request.",
                400);
        }

        $eventDispatcher = $this->container->get('event_dispatcher');

        $passwordChaneRequestedEvent = new UserPasswordRetrievalRequestedEvent($user);
        $eventDispatcher->dispatch(UserPasswordRetrievalRequestedEvent::NAME, $passwordChaneRequestedEvent);
    }

    /**
     * @return User
     */
    public function setPassword()
    {
        return $this->user;
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles)
    {
        $this->user->getAccessInfo()->setRoles($roles);
    }

    /**
     * @return boolean
     */
    public function hasAdminRoles()
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function hasSuperAdminRoles()
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function hasUserRoles()
    {
        return true;
    }

}