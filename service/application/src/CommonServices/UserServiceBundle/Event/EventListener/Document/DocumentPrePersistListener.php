<?php

namespace CommonServices\UserServiceBundle\Event\EventListener\Document;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\PhoneNumber;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\lib\Utility\MobileNumberFormatter;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CommonServices\UserServiceBundle\Event\UserMobileNumberChangedEvent;
use CommonServices\UserServiceBundle\Event\UserNameChangedEvent;
use CommonServices\UserServiceBundle\Event\UserPasswordChangedEvent;
use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;

/**
 * Class DocumentPrePersistListener
 * @package CommonServices\UserServiceBundle\Event\EventListener\Document
 */
class DocumentPrePersistListener
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

        if ($document instanceof User) {

            $nameChangedEvent = new UserNameChangedEvent($document);
            $eventDispatcher->dispatch(UserNameChangedEvent::NAME, $nameChangedEvent);

            $mobileNumberChangedEvent = new UserMobileNumberChangedEvent($document->getMobileNumber());
            $eventDispatcher->dispatch(UserMobileNumberChangedEvent::NAME, $mobileNumberChangedEvent);


            $passwordChangedEvent = new UserPasswordChangedEvent($document->getAccessInfo());
            $eventDispatcher->dispatch(UserPasswordChangedEvent::NAME, $passwordChangedEvent);

            $passwordChangedEvent = new UserPasswordChangedEvent($document->getAccessInfo());
            $eventDispatcher->dispatch(UserPasswordChangedEvent::NAME, $passwordChangedEvent);

            /** @var AccessInfo $accessInfo */
            $accessInfo = $document->getAccessInfo();
            $accessInfo->setRoles(['ROLE_USER']);
            $document->setUuid(Uuid::uuid4()->toString());
        }
    }
}