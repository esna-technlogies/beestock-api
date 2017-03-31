<?php

namespace CommonServices\UserServiceBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use CommonServices\UserServiceBundle\DependencyInjection\Security\Factory\WsseFactory;

/**
 * Class UserServiceBundle
 * @package CommonServices\UserServiceBundle
 */
class UserServiceBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /**
            $extension = $container->getExtension('security');
            $extension->addSecurityListenerFactory(new WsseFactory());
         ***/
    }
}
