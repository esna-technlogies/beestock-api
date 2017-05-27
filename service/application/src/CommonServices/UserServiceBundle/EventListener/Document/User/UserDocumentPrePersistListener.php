<?php

namespace CommonServices\UserServiceBundle\EventListener\Document\User;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\User\Email\UserEmailAddedToAccountEvent;
use CommonServices\UserServiceBundle\Event\User\MobileNumber\UserMobileNumberChangeRequestedEvent;
use CommonServices\UserServiceBundle\Event\User\Account\UserAccountInitializedEvent;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CommonServices\UserServiceBundle\Event\User\Name\UserNameChangedEvent;
use CommonServices\UserServiceBundle\Event\User\Password\UserPasswordChangedEvent;

/**
 * Class UserDocumentPrePersistListener
 * @package CommonServices\UserServiceBundle\EventListener\Document\User
 */
class UserDocumentPrePersistListener
{
    /**
     * @var ContainerInterface
     */
    public $serviceContainer;

    /**
     * DocumentPrePersistListener constructor.
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $eventDispatcher = $this->serviceContainer->get('event_dispatcher');

        if ($document instanceof User)
        {
            $userCreatedEvent = new UserAccountInitializedEvent($document);
            $eventDispatcher->dispatch(UserAccountInitializedEvent::NAME, $userCreatedEvent);

            $nameChangedEvent = new UserNameChangedEvent($document);
            $eventDispatcher->dispatch(UserNameChangedEvent::NAME, $nameChangedEvent);

            $passwordChangedEvent = new UserPasswordChangedEvent($document->getAccessInfo());
            $eventDispatcher->dispatch(UserPasswordChangedEvent::NAME, $passwordChangedEvent);
        }
    }
}