<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 03/04/2017
 * Time: 2:57 PM
 */

namespace CommonServices\UserServiceBundle\EventBus\Consumer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class UserAccountChangeConsumer
 * @package CommonServices\UserServiceBundle\EventBus\Consumer
 */
class UserAccountChangeConsumer implements ConsumerInterface
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
    public function execute(AMQPMessage $msg)
    {

        $unSerializedMessage = unserialize($msg);

        var_dump($unSerializedMessage);

    }
}
