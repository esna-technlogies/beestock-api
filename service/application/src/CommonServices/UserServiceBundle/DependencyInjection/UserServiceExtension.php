<?php

namespace CommonServices\UserServiceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class UserServiceExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('api_format', $config['api_settings']['api_format']);
        $container->setParameter('api_url', $config['api_settings']['api_url']);

        $container->setParameter('facebook_settings', $config['social_login']['facebook']);
        $container->setParameter('google_settings', $config['social_login']['google']);


/*        $container->setParameter('ssl_public_key', $config['security']['jwt']['ssl_public_key']);
        $container->setParameter('ssl_private_key', $config['security']['jwt']['ssl_private_key']);
        $container->setParameter('ssl_key_pass_phrase', $config['security']['jwt']['ssl_key_pass_phrase']);
        $container->setParameter('jwt_token_ttl', $config['security']['jwt']['jwt_token_ttl']);*/

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
