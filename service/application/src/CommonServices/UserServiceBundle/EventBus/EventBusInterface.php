<?php

namespace CommonServices\UserServiceBundle\EventBus;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Interface EventBusInterface
 * @package CommonServices\UserServiceBundle\EventBus
 */
interface EventBusInterface
{
    /**
     * EventBusInterface constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container);

    /**
     * @param ChangeRequest $changeRequest
     * @param string $producerName
     * @return mixed
     */
    public function publish(ChangeRequest $changeRequest, string $producerName);

    /**
     * @return mixed
     */
    public function consume();
}