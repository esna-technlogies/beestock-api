<?php

namespace CommonServices\UserServiceBundle\EventListener\Document\User;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\PhoneNumber;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\UserEmail\UserEmailChangeRequestedEvent;
use CommonServices\UserServiceBundle\Event\UserMobileNumberChangedEvent;
use CommonServices\UserServiceBundle\Event\UserNameChangedEvent;
use CommonServices\UserServiceBundle\Event\UserPasswordChangedEvent;
use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserDocumentPreUpdateListener
 * @package CommonServices\UserServiceBundle\EventListener\Document
 */
class UserDocumentPreUpdateListener
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
                $oldValue = $event->getOldValue('email');
                $newValue = $event->getNewValue('email');

                $emailChangedEvent = new UserEmailChangeRequestedEvent($document, $newValue, $oldValue);
                $eventDispatcher->dispatch(UserEmailChangeRequestedEvent::NAME, $emailChangedEvent);

                // Don't change email until the new email is verified
                $event->setNewValue('email', $oldValue);
            }
        }

        // sub-document (embedded) of the User document
        if ($document instanceof AccessInfo)
        {
            if ($event->hasChangedField('password'))
            {
                $passwordChangedEvent = new UserPasswordChangedEvent($document);
                $eventDispatcher->dispatch(UserPasswordChangedEvent::NAME, $passwordChangedEvent);
            }
        }

        // sub-document (embedded) of the User document
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