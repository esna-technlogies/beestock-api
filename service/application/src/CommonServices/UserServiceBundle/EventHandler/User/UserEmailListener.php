<?php

namespace CommonServices\UserServiceBundle\EventHandler\User;

use CommonServices\UserServiceBundle\Domain\User\UserDomain;
use CommonServices\UserServiceBundle\Event\User\Email\UserEmailAddedToAccountEvent;
use CommonServices\UserServiceBundle\Event\User\Email\UserEmailChangedEvent;
use CommonServices\UserServiceBundle\Event\User\Email\UserEmailChangeRequestedEvent;
use CommonServices\UserServiceBundle\Domain\ChangeRequest\ChangeRequestDomain;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserEmailChangedListener
 * @package CommonServices\UserServiceBundle\Event\EventHandler\User
 */
class UserEmailListener implements EventSubscriberInterface
{
    /**
     * @var ChangeRequestDomain
     */
    private $changeRequestsService;
    /**
     * @var UserDomain
     */
    private $userManagerService;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * UserPasswordChangedListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    )
    {
        $this->container = $container;
        $this->userManagerService    = $this->container->get('user_service.user_domain');
        $this->changeRequestsService = $this->container->get('user_service.change_request_domain');
    }

    /**
     * @param Event $event
     */
    public function onUserEmailChangeRequested(Event $event)
    {
        /** @var UserEmailChangeRequestedEvent $event */
        $userDocument = $event->getUser();
        $user = $this->userManagerService->getUser($userDocument);

        /// issue a change request event
        $requestLifeTime = 1 * 60 * 60;
        $user->getAccount()->issueAccountChangeRequest(
            UserEmailChangeRequestedEvent::NAME,
            $requestLifeTime,
            $event->getOldValue(),
            $event->getNewValue()
        );
    }

    /**
     * @param Event $event
     */
    public function onUserEmailAddedToAccount(Event $event)
    {
        /** @var UserEmailAddedToAccountEvent $event */
//        $userDocument = $event->getUser();
//        $user = $this->userManagerService->getUser($userDocument);
//
//        /// issue a change request event
//        $requestLifeTime = 1 * 60 * 60;
//        $user->getAccount()->issueAccountChangeRequest(
//            UserEmailAddedToAccountEvent::NAME,
//            $requestLifeTime,
//            '',
//            $event->getEmail()
//        );
    }

    /**
     * @param Event $event
     */
    public function onUserEmailChanged(Event $event)
    {
        // Do something ..
        $event->stopPropagation();
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserEmailAddedToAccountEvent::NAME => array(
                array('onUserEmailAddedToAccount', 1),
            ),
            UserEmailChangeRequestedEvent::NAME => array(
                array('onUserEmailChangeRequested', 1),
            ),
            UserEmailChangedEvent::NAME => array(
                array('onUserEmailChangeRequested', 1),
            ),
        );
    }
}