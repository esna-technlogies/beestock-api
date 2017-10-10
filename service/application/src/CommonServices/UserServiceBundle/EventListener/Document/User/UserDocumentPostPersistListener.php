<?php

namespace CommonServices\UserServiceBundle\EventListener\Document\User;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\User\Account\UserAccountSuccessfullyCreatedEvent;
use CommonServices\UserServiceBundle\Event\User\Email\UserEmailAddedToAccountEvent;
use CommonServices\UserServiceBundle\Event\User\MobileNumber\UserMobileNumberChangedEvent;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserDocumentPostPersistListener
 * @package CommonServices\UserServiceBundle\EventListener\Document
 */
class UserDocumentPostPersistListener
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
    public function postPersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $eventDispatcher = $this->serviceContainer->get('event_dispatcher');

        if ($document instanceof User)
        {

            $mobileNumberChangedEvent = new UserMobileNumberChangedEvent($document);
            $eventDispatcher->dispatch(UserMobileNumberChangedEvent::NAME, $mobileNumberChangedEvent);

            $emailAddedToAccountEvent = new UserEmailAddedToAccountEvent($document, $document->getEmail());
            $eventDispatcher->dispatch(UserEmailAddedToAccountEvent::NAME, $emailAddedToAccountEvent);

            $userCreatedEvent = new UserAccountSuccessfullyCreatedEvent($document);
            $eventDispatcher->dispatch(UserAccountSuccessfullyCreatedEvent::NAME, $userCreatedEvent);

        }
    }
}