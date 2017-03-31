<?php

namespace CommonServices\UserServiceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder
            ->root('user_service')
            ->children()
                ->arrayNode('api_settings')
                    ->children()
                        ->scalarNode('api_format')->isRequired()->end()
                        ->scalarNode('api_url')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('social_login')
                    ->children()
                        ->arrayNode('facebook')->isRequired()
                            ->children()
                            ->scalarNode('app_id')->isRequired()->end()
                            ->scalarNode('app_secret')->isRequired()->end()
                            ->scalarNode('sdk_version')->isRequired()->end()
                            ->end()
                        ->end()
                        ->arrayNode('google')->isRequired()
                            ->children()
                                ->scalarNode('app_id')->isRequired()->end()
                                ->scalarNode('app_secret')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('security')
                    ->children()
                        ->arrayNode('jwt')->isRequired()
                            ->children()
                                ->scalarNode('ssl_public_key')->isRequired()->end()
                                ->scalarNode('ssl_private_key')->isRequired()->end()
                                ->scalarNode('ssl_key_pass_phrase')->isRequired()->end()
                                ->scalarNode('jwt_token_ttl')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
