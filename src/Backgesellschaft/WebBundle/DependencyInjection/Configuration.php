<?php

/**
 * @author    Markus Tacker <m@coderbyheart.de>
 * @copyright 2013 Backgesellschaft | http://backgesellschaft.de/
 */

namespace Backgesellschaft\WebBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from the app/config files.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('Backgesellschaft_web');
        $rootNode
            ->children()
            ->scalarNode('content_dir')->defaultValue('%kernel.root_dir%/../web/content')->end()
            ->scalarNode('content_path')->defaultValue('/content')->end()
            ->end();
        return $treeBuilder;
    }
}
