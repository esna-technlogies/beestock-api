<?php

namespace CommonServices\UserServiceBundle\lib;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\UserPasswordRetrievalRequestedEvent;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserSecurityService
 * @package CommonServices\UserServiceBundle\lib
 */
class UserSecurityService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * ItemService constructor.
     * @param ContainerInterface $serviceContainer
     * @param UserRepository $userRepository
     */
    public function __construct(ContainerInterface $serviceContainer, UserRepository $userRepository)
    {
        $this->serviceContainer = $serviceContainer;
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $userName
     * @throws InvalidArgumentException
     *
     * @return null
     */
    public function retrievePassword(string $userName = '')
    {
        /** @var User $user */
        $user = $this->serviceContainer->get('user_service.core')->getUserByUserName($userName);

        $user->getAccessInfo()->setLastPasswordRetrievalRequest(time());

        $eventDispatcher = $this->serviceContainer->get('event_dispatcher');

        $passwordChaneRequestedEvent = new UserPasswordRetrievalRequestedEvent($user);
        $eventDispatcher->dispatch(UserPasswordRetrievalRequestedEvent::NAME, $passwordChaneRequestedEvent);
    }

    /**
     * @param User $user
     * @param string $password
     *
     * @return bool
     */
    public function hasValidCredentials(User $user, string $password) : bool
    {
        /** @var AccessInfo $accessInfo */
        $accessInfo = $user->getAccessInfo();

        $isValid = $this->serviceContainer
            ->get('security.password_encoder')
            ->isPasswordValid($accessInfo, $password);

        if (!$isValid) {
            return false;
        }

        return true;
    }
}
