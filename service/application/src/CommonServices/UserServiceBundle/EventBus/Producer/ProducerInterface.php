<?php

namespace CommonServices\UserServiceBundle\EventBus\Producer;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Interface ProducerInterface
 * @package CommonServices\UserServiceBundle\EventBus\Producer
 */
interface ProducerInterface
{
    /**
     * ProducerInterface constructor.
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(ContainerInterface $serviceContainer);

    /**
     * @param string $publishedMessage
     * @param string $queueUrl
     * @param string $messageGroupId
     * @return mixed
     */
    public function publish(string $publishedMessage, string $queueUrl, string $messageGroupId);
}