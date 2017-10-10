<?php

namespace CommonServices\UserServiceBundle\EventHandler\User;

use CommonServices\UserServiceBundle\Event\User\Account\UserAccountActivatedEvent;
use CommonServices\UserServiceBundle\Event\User\Account\UserAccountSuccessfullyCreatedEvent;
use CommonServices\UserServiceBundle\Event\User\Account\UserAccountInitializedEvent;
use CommonServices\UserServiceBundle\Security\Roles\UserRolesManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserAccountListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * UserAccountListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Event $event
     */
    public function onUserAccountSuccessfullyCreated(Event $event)
    {
        /** @var UserAccountSuccessfullyCreatedEvent $event */
        $userDocument = $event->getUser();

        $user = $this->container->get('user_service.user_domain')->getUser($userDocument);
        /// issue a change request event
        $requestLifeTime = 24 * 1 * 60 * 60;
        $user->getAccount()->issueAccountChangeRequest(
            UserAccountSuccessfullyCreatedEvent::NAME,
            $requestLifeTime,
            '',
            $event->getUser()->getEmail()
        );

        $this->container->get('aws.user.sns')->publishUserEvent($userDocument->getUuid(), UserAccountSuccessfullyCreatedEvent::NAME);

    }

    /**
     * @param Event $event
     */
    public function onUserAccountInitialized(Event $event)
    {
        /** @var UserAccountSuccessfullyCreatedEvent $event */
        $userDocument = $event->getUser();
        $userService = $this->container->get('user_service.user_domain');
        $userService->getUser($userDocument)->getSecurity()->updateUserRoles(UserRolesManager::getInactiveUserRoles());
    }

    /**
     * @param Event $event
     */
    public function onUserAccountActivated(Event $event)
    {
        /** @var UserAccountSuccessfullyCreatedEvent $event */
        $userDocument = $event->getUser();
        $userService = $this->container->get('user_service.user_domain');
        $userService->getUser($userDocument)->getSecurity()->updateUserRoles(UserRolesManager::getStandardActiveUserRoles());
        $userService->getUserRepository()->save($userDocument);

        /// issue a change request event
        $requestLifeTime = 1 * 60 * 60;
        $userService->getUser($userDocument)->getAccount()->issueAccountChangeRequest(
            UserAccountActivatedEvent::NAME,
            $requestLifeTime,
            '',
            ''
        );

        $this->container->get('aws.user.sns')->publishUserEvent($userDocument->getUuid(), UserAccountActivatedEvent::NAME);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserAccountSuccessfullyCreatedEvent::NAME => array(
                array('onUserAccountSuccessfullyCreated', 1),
            ),
            UserAccountInitializedEvent::NAME => array(
                array('onUserAccountInitialized', 1),
            ),
            UserAccountActivatedEvent::NAME => array(
                array('onUserAccountActivated', 1),
            ),
        );
    }
}