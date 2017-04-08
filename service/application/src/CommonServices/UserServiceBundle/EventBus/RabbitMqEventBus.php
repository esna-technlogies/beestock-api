<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 07/04/2017
 * Time: 7:16 AM
 */

namespace CommonServices\UserServiceBundle\EventBus;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RabbitMqEventBus
 * @package CommonServices\UserServiceBundle\EventBus
 */
class RabbitMqEventBus implements EventBusInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @inheritdoc
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function publish(ChangeRequest $changeRequest)
    {
        $this->container
            ->get('old_sound_rabbit_mq.'.$changeRequest->getChangeProcessorName().'_producer')
            ->publish(serialize($changeRequest));
    }

    /**
     * @inheritdoc
     */
    public function consume()
    {
        // TODO: Implement consume() method.
    }
}