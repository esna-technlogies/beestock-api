<?php

namespace CommonServices\UserServiceBundle\Event\EventHandler\UserEvent;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Event\UserPasswordChangedEvent;
use CommonServices\UserServiceBundle\Event\UserPasswordRetrievalRequestedEvent;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserPasswordRetrievalRequestedListener
 * @package CommonServices\UserServiceBundle\Event\EventHandler\UserEvent
 */
class UserPasswordRetrievalRequestedListener implements EventSubscriberInterface
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
     *
     * @throws InvalidArgumentException
     */
    public function onUserPasswordRetrievalRequested(Event $event)
    {
        /** @var UserPasswordRetrievalRequestedEvent $event */
        $accessInfo = $event->getUser()->getAccessInfo();

        $lastPasswordChange = $accessInfo->getLastPasswordRetrievalRequest();

        // current time - 10800 (seconds in 3 hours)
        if ($lastPasswordChange >= (time() - 10800)) {
            throw new InvalidArgumentException("Changing the password can't happen before 3 hours from last change request.",
                400);
        }

    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserPasswordRetrievalRequestedEvent::NAME => array(
                array('onUserPasswordRetrievalRequested', 1),
            ),
        );
    }
}