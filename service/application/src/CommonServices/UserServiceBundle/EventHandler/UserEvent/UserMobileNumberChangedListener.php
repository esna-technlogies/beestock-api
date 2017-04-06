<?php

namespace CommonServices\UserServiceBundle\EventHandler\UserEvent;

use CommonServices\UserServiceBundle\Event\UserMobileNumberChangedEvent;
use CommonServices\UserServiceBundle\Utility\MobileNumberFormatter;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserMobileNumberChangedListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * UserMobileNumberChangedListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->serviceContainer = $container;
    }

    /**
     * @param Event $event
     */
    public function onUserMobileNumberChanged(Event $event)
    {
        /** @var UserMobileNumberChangedEvent $event */
        $mobileNumber = $event->getMobileNumber();

        $internationalMobileNumber =
            (new MobileNumberFormatter())
                ->getInternationalMobileNumber($mobileNumber->getNumber(), $mobileNumber->getCountryCode());

        $mobileNumber->setInternationalNumber($internationalMobileNumber);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserMobileNumberChangedEvent::NAME => array(
                array('onUserMobileNumberChanged', 1),
            ),
        );
    }
}