<?php

namespace CommonServices\PhotoBundle\EventListener\Document\Photo;


use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PhotoDocumentPreUpdateListener
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

    }
}