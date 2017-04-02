<?php

namespace CommonServices\UserServiceBundle;

use CommonServices\UserServiceBundle\DependencyInjection\Security\Factory\JwtFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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

        $extension = $container->getExtension('security');

        /** @var SecurityExtension  $extension*/
        $extension->addSecurityListenerFactory(new JwtFactory());
    }
}
