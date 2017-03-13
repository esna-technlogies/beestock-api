<?php

namespace CommonServices\UserServiceBundle\Event\EventHandler\UserEvent;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Event\UserCreatedEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ramsey\Uuid\Uuid;

class UserCreatedListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * UserCreatedListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->serviceContainer = $container;
    }

    /**
     * @param Event $event
     */
    public function onUserCreated(Event $event)
    {
        /** @var UserCreatedEvent $event */
        $user = $event->getUser();

         /** @var AccessInfo $accessInfo */
        $accessInfo = $user->getAccessInfo();
        $accessInfo->setRoles(['ROLE_USER']);
        $user->setUuid(Uuid::uuid4()->toString());
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserCreatedEvent::NAME => array(
                array('onUserCreated', 1),
            ),
        );
    }
}