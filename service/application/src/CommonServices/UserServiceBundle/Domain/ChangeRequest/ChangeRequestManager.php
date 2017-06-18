<?php

namespace CommonServices\UserServiceBundle\Domain\ChangeRequest;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\User\Account\UserAccountActivatedEvent;
use CommonServices\UserServiceBundle\Event\User\Account\UserAccountSuccessfullyCreatedEvent;
use CommonServices\UserServiceBundle\Event\User\Email\UserEmailAddedToAccountEvent;
use CommonServices\UserServiceBundle\Event\User\Email\UserEmailChangedEvent;
use CommonServices\UserServiceBundle\Event\User\Email\UserEmailChangeRequestedEvent;
use CommonServices\UserServiceBundle\Event\User\MobileNumber\UserMobileNumberChangeRequestedEvent;
use CommonServices\UserServiceBundle\Event\User\Password\UserPasswordChangedEvent;
use CommonServices\UserServiceBundle\Event\User\Password\UserPasswordRetrievalRequestedEvent;
use CommonServices\UserServiceBundle\Event\User\Password\UserRandomPasswordGeneratedEvent;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use CommonServices\UserServiceBundle\Repository\ChangeRequestRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ChangeRequestManager
 * @package CommonServices\UserServiceBundle\Domain\ChangeRequest\Manager
 */
class ChangeRequestManager
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ChangeRequest
     */
    private $changeRequest;
    /**
     * @var ChangeRequestRepository
     */
    private $changeRequestRepository;

    /**
     * ChangeRequestManager constructor.
     * @param ContainerInterface $container
     * @param ChangeRequest $changeRequest
     * @param ChangeRequestRepository $changeRequestRepository
     */
    public function __construct(ContainerInterface $container, ChangeRequest $changeRequest, ChangeRequestRepository $changeRequestRepository)
    {
        $this->container = $container;
        $this->changeRequest = $changeRequest;
        $this->changeRequestRepository = $changeRequestRepository;
    }

    /**
     * delete
     */
    public function delete()
    {
        $this->changeRequestRepository->delete($this->changeRequest);
    }

    /**
     * notified
     */
    public function notified()
    {
        $this->changeRequest->setUserNotified(true);
        $this->changeRequestRepository->save($this->changeRequest);
    }


    /**
     * @param string $verificationCode
     * @throws NotFoundException
     *
     * executeChange
     */
    public function executeChange(string $verificationCode)
    {
        // delete the request if it has already expired ..
        if(time() > $this->changeRequest->getEventLifeTime())
        {
            $this->delete();
            throw new NotFoundException('Change Request not found ..');
        }

        // verify te code first ..
        if ($this->changeRequest->getVerificationCode() !== $verificationCode){
                throw new NotFoundException('Change Request not found ..');
        }


        $eventName = $this->changeRequest->getEventName();
        $eventDispatcher = $this->container->get('event_dispatcher');

        /** @var User $user */
        $user = $this->container->get('user_service.user_domain')->getUserRepository()->findByUuid($this->changeRequest->getUser());

        if(UserPasswordRetrievalRequestedEvent::NAME === $eventName)
        {
            // fire change password, and send new one to user
            $userActivatedEvent = new UserRandomPasswordGeneratedEvent($user);
            $eventDispatcher->dispatch(UserRandomPasswordGeneratedEvent::NAME, $userActivatedEvent);
        }


        // Account verification and welcome events
        if(UserAccountSuccessfullyCreatedEvent::NAME === $eventName)
        {
            // fire activate account, add user to the activated group
            $userActivatedEvent = new UserAccountActivatedEvent($user);
            $eventDispatcher->dispatch(UserAccountActivatedEvent::NAME, $userActivatedEvent);
        }

        // change email address

        if(UserEmailChangeRequestedEvent::NAME === $eventName)
        {
            // fire activate account, add user to the activated group
            $userActivatedEvent = new UserEmailChangedEvent($user, $this->changeRequest->getNewValue(), $this->changeRequest->getOldValue());
            $eventDispatcher->dispatch(UserEmailChangedEvent::NAME, $userActivatedEvent);
        }


        // Change mobile number
        if(UserMobileNumberChangeRequestedEvent::NAME === $eventName)
        {


        }



        // change email address
        if(UserEmailAddedToAccountEvent::NAME === $eventName)
        {



        }


        $this->delete();
    }

}