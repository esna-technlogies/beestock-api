<?php

namespace CommonServices\UserServiceBundle\EventListener\Document;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\User\Account\UserAccountSuccessfullyCreatedEvent;
use CommonServices\UserServiceBundle\Event\UserEmail\UserEmailAddedToAccountEvent;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DocumentPrePersistListener
 * @package CommonServices\UserServiceBundle\Event\EventListener\Document
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
            $userCreatedEvent = new UserAccountSuccessfullyCreatedEvent($document);
            $eventDispatcher->dispatch(UserAccountSuccessfullyCreatedEvent::NAME, $userCreatedEvent);

            $nameChangedEvent = new UserEmailAddedToAccountEvent($document, $document->getEmail());
            $eventDispatcher->dispatch(UserEmailAddedToAccountEvent::NAME, $nameChangedEvent);
        }
    }
}