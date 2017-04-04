<?php

namespace CommonServices\UserServiceBundle\Event\EventListener\Document;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\PhoneNumber;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\UserEmailChangedEvent;
use CommonServices\UserServiceBundle\Event\UserMobileNumberChangedEvent;
use CommonServices\UserServiceBundle\Event\UserNameChangedEvent;
use CommonServices\UserServiceBundle\Event\UserPasswordChangedEvent;
use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DocumentPreUpdateListener
 * @package CommonServices\UserServiceBundle\Event\EventListener\Document
 */
class DocumentPreUpdateListener
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
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(PreUpdateEventArgs $event)
    {
        $document = $event->getDocument();
        $eventDispatcher = $this->serviceContainer->get('event_dispatcher');

        if ($document instanceof User)
        {
            if ($event->hasChangedField('firstName') || $event->hasChangedField('lastName'))
            {
                $nameChangedEvent = new UserNameChangedEvent($document);
                $eventDispatcher->dispatch(UserNameChangedEvent::NAME, $nameChangedEvent);
            }

            if ($event->hasChangedField('email'))
            {
                $nameChangedEvent = new UserEmailChangedEvent($document);
                $eventDispatcher->dispatch(UserEmailChangedEvent::NAME, $nameChangedEvent);
            }
        }

        if ($document instanceof AccessInfo)
        {
            if ($event->hasChangedField('password'))
            {
                $passwordChangedEvent = new UserPasswordChangedEvent($document);
                $eventDispatcher->dispatch(UserPasswordChangedEvent::NAME, $passwordChangedEvent);
            }
        }

        if ($document instanceof PhoneNumber)
        {
            if ($event->hasChangedField('country_code') || $event->hasChangedField('number'))
            {
                $mobileNumberChangedEvent = new UserMobileNumberChangedEvent($document);
                $eventDispatcher->dispatch(UserMobileNumberChangedEvent::NAME, $mobileNumberChangedEvent);
            }
        }

        $dm = $event->getDocumentManager();
        $class = $dm->getClassMetadata(get_class($document));
        $dm->getUnitOfWork()->recomputeSingleDocumentChangeSet($class, $document);
    }
}