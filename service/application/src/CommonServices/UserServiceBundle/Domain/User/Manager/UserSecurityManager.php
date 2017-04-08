<?php

namespace CommonServices\UserServiceBundle\Domain\User\Manager;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\User\Password\UserPasswordRetrievalRequestedEvent;
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
        $userDocument = $this->user;

        $lastTimePasswordChangeRequest = $userDocument->getAccessInfo()->getLastPasswordRetrievalRequest();

        // current time - 10800 (seconds in 3 hours)
        if ($lastTimePasswordChangeRequest >= (time() - 60)) {
            throw new InvalidArgumentException("A password retrieval request has been issued less than a minute ago ..",
                400);
        }
        $eventDispatcher = $this->container->get('event_dispatcher');

        $passwordChaneRequestedEvent = new UserPasswordRetrievalRequestedEvent($userDocument);
        $eventDispatcher->dispatch(UserPasswordRetrievalRequestedEvent::NAME, $passwordChaneRequestedEvent);
    }


    /**
     * @param null $time
     */
    public function updatePasswordRetrievalLimits($time = null)
    {
        if(!isset($time))
        {
            $time = time();
        }
        $this->user->getAccessInfo()->setLastPasswordRetrievalRequest($time);
        $this->userRepository->save($this->user);
    }

    /**
     * @param array $roles
     */
    public function updateUserRoles(array $roles)
    {
        $this->user->getAccessInfo()->setRoles($roles);
    }

}