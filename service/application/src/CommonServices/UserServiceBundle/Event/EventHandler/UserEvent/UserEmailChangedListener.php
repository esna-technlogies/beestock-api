<?php

namespace CommonServices\UserServiceBundle\Event\EventHandler\UserEvent;

use CommonServices\UserServiceBundle\Event\UserEmailChangedEvent;
use CommonServices\UserServiceBundle\lib\UserSecurityService;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserEmailChangedListener
 * @package CommonServices\UserServiceBundle\Event\EventHandler\UserEvent
 */
class UserEmailChangedListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * UserEmailChangedListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->serviceContainer = $container;
    }

    /**
     * @param Event $event
     */
    public function onUserEmailChanged(Event $event)
    {
        /** @var UserEmailChangedEvent $event */
        $user = $event->getUser();
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserEmailChangedEvent::NAME => array(
                array('onUserEmailChanged', 1),
            ),
        );
    }
}