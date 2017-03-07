<?php

namespace Nicosomb\WallabagCamoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nicosomb_wallabag_camo');

        $rootNode
            ->children()
                ->scalarNode('key')
                    ->defaultValue('yourCamoKey')
                ->end()
                ->scalarNode('domain')
                    ->defaultValue('static.yourwallabag.com')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
