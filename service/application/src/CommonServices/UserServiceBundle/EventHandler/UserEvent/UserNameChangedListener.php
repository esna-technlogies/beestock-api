<?php

namespace CommonServices\UserServiceBundle\EventHandler\UserEvent;

use CommonServices\UserServiceBundle\Event\UserNameChangedEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserNameChangedListener
 * @package CommonServices\UserServiceBundle\EventHandler\UserEvent
 */
class UserNameChangedListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * UserPasswordChangedListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->serviceContainer = $container;
    }

    /**
     * @param Event $event
     */
    public function onUserNameChanged(Event $event)
    {
        /** @var UserNameChangedEvent $event */
        $user = $event->getUser();

        $user->setFullName(trim($user->getFirstName().' '.$user->getLastName()));
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserNameChangedEvent::NAME => array(
                array('onUserNameChanged', 1),
            ),
        );
    }
}