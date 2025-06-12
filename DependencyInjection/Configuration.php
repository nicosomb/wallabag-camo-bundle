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
        $treeBuilder = new TreeBuilder('nicosomb_wallabag_camo');
        $rootNode = $treeBuilder->getRootNode();

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
