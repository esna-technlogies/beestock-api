<?php

namespace CommonServices\UserServiceBundle\Event\EventHandler\UserEvent;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Event\UserPasswordChangedEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserPasswordChangedListener implements EventSubscriberInterface
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
    public function onUserPasswordChanged(Event $event)
    {
        /** @var UserPasswordChangedEvent $event */
        $accessInfo = $event->getUserAccessInfo();

        /** @var AccessInfo $accessInfo */
        $accessInfo->setSalt(hash('sha256', time()));
        $userPassword = $accessInfo->getPassword();

        $encoder = $this->serviceContainer->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($accessInfo, $userPassword);

        $accessInfo->setPassword($encodedPassword);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserPasswordChangedEvent::NAME => array(
                array('onUserPasswordChanged', 1),
            ),
        );
    }
}