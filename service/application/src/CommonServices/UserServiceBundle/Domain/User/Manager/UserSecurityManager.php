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
    private $userDocument;
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
        $this->userDocument = $user;
        $this->userRepository = $userRepository;
        $this->container = $container;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function issueForgotPasswordRequest()
    {
        $lastTimePasswordChangeRequest = $this->userDocument->getAccessInfo()->getLastPasswordRetrievalRequest();

        // current time - 10800 (seconds in 3 hours)
        if ($lastTimePasswordChangeRequest >= (time() - 60)) {
            throw new InvalidArgumentException(
                "A password retrieval request has been issued less than a minute ago ..",
                400
            );
        }

        $user = $this->container->get('user_service.user_domain')->getUser($this->userDocument);
        $changeRequestService = $this->container->get('user_service.change_request_domain');

        // delete all previous password retrieval requests .. only one should be valid !
        $changeRequestService->getDomainService()->deleteAllPreviousSimilarRequests(
            $this->userDocument->getUuid(),
            UserPasswordRetrievalRequestedEvent::NAME
        );


        /// issue a new change request of user password
        $requestLifeTime = 1 * 60 * 60;
        $changeRequest =  $user->getAccount()->issueAccountChangeRequest(
            UserPasswordRetrievalRequestedEvent::NAME,
            $requestLifeTime
        );

        $eventDispatcher = $this->container->get('event_dispatcher');
        $passwordChaneRequestedEvent = new UserPasswordRetrievalRequestedEvent($this->userDocument);
        $eventDispatcher->dispatch(UserPasswordRetrievalRequestedEvent::NAME, $passwordChaneRequestedEvent);

        return $changeRequest;
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
        $this->userDocument->getAccessInfo()->setLastPasswordRetrievalRequest($time);
        $this->userRepository->save($this->userDocument);
    }

    /**
     * @param array $roles
     */
    public function updateUserRoles(array $roles)
    {
        $this->userDocument->getAccessInfo()->setRoles($roles);
    }

}