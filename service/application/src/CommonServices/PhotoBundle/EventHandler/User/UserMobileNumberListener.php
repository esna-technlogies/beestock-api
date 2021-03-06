<?php

namespace CommonServices\UserServiceBundle\EventHandler\User;

use CommonServices\UserServiceBundle\Event\User\MobileNumber\UserMobileNumberChangeRequestedEvent;
use CommonServices\UserServiceBundle\Event\User\MobileNumber\UserMobileNumberChangedEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserMobileNumberListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * UserMobileNumberChangedListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Event $event
     */
    public function onUserMobileNumberChanged(Event $event)
    {
        /** @var UserMobileNumberChangedEvent $event */
        $userDocument = $event->getUser();

        $mobileNumberDocument = $userDocument->getMobileNumber();
        $mobileNumber = $mobileNumberDocument->getNumber();
        $countryCode = $mobileNumberDocument->getCountryCode();

        $user = $this->container->get('user_service.user_domain')->getUser($userDocument);
        $user->getSettings()->setMobileNumberAlternatives($mobileNumber, $countryCode);

        //TODO : do something here
    }

    /**
     * @param Event $event
     */
    public function onUserMobileNumberChangeRequested(Event $event)
    {
        /** @var UserMobileNumberChangedEvent $event */
        $userDocument = $event->getUser();

        $mobileNumberDocument = $userDocument->getMobileNumber();
        $mobileNumber = $mobileNumberDocument->getNumber();
        $countryCode = $mobileNumberDocument->getCountryCode();

        $user = $this->container->get('user_service.user_domain')->getUser($userDocument);
        $user->getSettings()->setMobileNumberAlternatives($mobileNumber, $countryCode);

        /// issue a change request event
        $requestLifeTime = 1 * 60 * 60;
        $user->getAccount()->issueAccountChangeRequest(
            UserMobileNumberChangeRequestedEvent::NAME,
            $requestLifeTime,
            null,
            $mobileNumberDocument->getInternationalNumber()
        );
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
            UserMobileNumberChangeRequestedEvent::NAME => array(
                array('onUserMobileNumberChangeRequested', 1),
            ),
        );
    }
}