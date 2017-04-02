<?php

namespace CommonServices\UserServiceBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

/**
 * Class WsseFactory
 * @package CommonServices\UserServiceBundle\DependencyInjection\Security\Factory
 */
class JwtFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.jwt.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('jwt.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider))
        ;

        $listenerId = 'security.authentication.listener.jwt.'.$id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('jwt.security.authentication.listener'));

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'jwt';
    }

    public function addConfiguration(NodeDefinition $node)
    {
    }
}
