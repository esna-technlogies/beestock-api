<?php

namespace CommonServices\UserServiceBundle\EventHandler\UserEvent;

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
     * UserCreatedListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Event $event
     */
    public function onUserAccountCreated(Event $event)
    {
        // Do something here ..
    }

    /**
     * @param Event $event
     */
    public function onUserAccountInitialized(Event $event)
    {
        /** @var UserAccountSuccessfullyCreatedEvent $event */
        $userDocument = $event->getUser();
        $userService = $this->container->get('user_service.user_domain');
        $userService->getUser($userDocument)->getSecurity()->setRoles(UserRolesManager::getInactiveUserRoles());
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserAccountSuccessfullyCreatedEvent::NAME => array(
                array('onUserAccountCreated', 1),
            ),
            UserAccountInitializedEvent::NAME => array(
                array('onUserAccountInitialized', 1),
            ),
        );
    }
}