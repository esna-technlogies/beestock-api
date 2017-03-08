<?php

namespace CommonServices\UserServiceBundle\Event\EventListener\Document;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\User;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Ramsey\Uuid\Uuid;

class DocumentPrePersistListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();

        if ($document instanceof User) {

            /** @var User $document */
            $document->setFullName(trim($document->getFirstName()." ".$document->getLastName()));

            $document->setUuid(Uuid::uuid4()->toString());

            /** @var AccessInfo $accessInfo */
            $accessInfo = $document->getAccessInfo();

            $accessInfo->setRoles(['ROLE_USER']);

            $accessInfo->setSalt(hash('sha256', time()));
        }
    }
}