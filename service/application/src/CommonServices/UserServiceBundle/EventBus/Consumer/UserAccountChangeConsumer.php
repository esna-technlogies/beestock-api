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
 * Class UserAccountChangeConsumer
 * @package CommonServices\UserServiceBundle\EventBus\Consumer
 */
class UserAccountChangeConsumer
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