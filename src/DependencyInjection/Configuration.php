<?php

/*
 * This file is part of the RollerworksPasswordStrengthBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Bundle\PasswordStrengthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rollerworks_password_strength');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC for Symfony < 4.2
            $rootNode = $treeBuilder->root('rollerworks_password_strength');
        }

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('blacklist')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default_provider')->defaultValue('rollerworks_password_strength.blacklist.provider.noop')->end()
                        ->arrayNode('providers')
                            ->fixXmlConfig('provider')
                            ->children()
                                ->arrayNode('sqlite')
                                    ->children()
                                        ->scalarNode('dsn')->defaultNull()->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('chain')
                                    ->children()
                                        ->booleanNode('lazy')->defaultFalse()->end()
                                        ->arrayNode('providers')
                                            ->fixXmlConfig('provider')
                                            ->prototype('scalar')->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('array')->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
