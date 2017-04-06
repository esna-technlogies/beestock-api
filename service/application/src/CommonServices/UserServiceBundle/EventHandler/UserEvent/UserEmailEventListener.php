<?php

namespace CommonServices\UserServiceBundle\EventHandler\UserEvent;

use CommonServices\UserServiceBundle\Event\UserEmail\UserEmailAddedToAccountEvent;
use CommonServices\UserServiceBundle\Event\UserEmail\UserEmailChangedEvent;
use CommonServices\UserServiceBundle\Event\UserEmail\UserEmailChangeRequestedEvent;
use CommonServices\UserServiceBundle\Domain\ChangeRequest\ChangeRequestDomain;
use CommonServices\UserServiceBundle\Utility\Security\RandomCodeGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserEmailChangedListener
 * @package CommonServices\UserServiceBundle\Event\EventHandler\UserEvent
 */
class UserEmailEventListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ChangeRequestDomain
     */
    private $changeRequestsService;

    /**
     * UserPasswordChangedListener constructor.
     * @param ContainerInterface $container
     * @param ChangeRequestDomain $changeRequestsService
     */
    public function __construct(
        ContainerInterface $container,
        ChangeRequestDomain $changeRequestsService
    )
    {
        $this->container = $container;
        $this->changeRequestsService   = $changeRequestsService;
    }

    /**
     * @param Event $event
     */
    public function onUserEmailChangeRequested(Event $event)
    {
        /** @var UserEmailChangeRequestedEvent $event */
        $user = $event->getUser();

        $verificationCode = RandomCodeGenerator::generateRandomVerificationString();

        $oldValue = $event->getOldValue();
        $newValue = $event->getNewValue();

        $requestLifeTime = 1 * 60 * 60;

        // notify user by sms
        $smsChangeRequestNotification = $this->changeRequestsService->generateChangeRequest(
            $user,
            $verificationCode,
            UserEmailChangeRequestedEvent::NAME,
            $requestLifeTime,
            ChangeRequestDomain::USER_NOTIFICATION_SMS,
            $oldValue,
            $newValue
        );

        // notify user by email
        $emailChangeRequestNotification = $this->changeRequestsService->generateChangeRequest(
            $user,
            $verificationCode,
            UserEmailChangeRequestedEvent::NAME,
            $requestLifeTime,
            ChangeRequestDomain::USER_NOTIFICATION_EMAIL,
            $oldValue,
            $newValue
        );

        $event->stopPropagation();
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
     * @param Event $event
     */
    public function onUserEmailAddedToAccount(Event $event)
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