<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 03/04/2017
 * Time: 2:57 PM
 */

namespace CommonServices\UserServiceBundle\EventBus\Consumer;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EmailChangeEventConsumer
 * @package CommonServices\UserServiceBundle\EventBus\Consumer
 */
class EmailChangeEventConsumer
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * @inheritdoc
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function consume()
    {
        print 'hello';
    }
}