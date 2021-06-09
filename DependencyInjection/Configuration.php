<?php

/**
 * This file is part of the pd-admin pd-api package.
 *
 * @package     pd-api
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-api
 */

namespace Pd\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('pd_api');
        $rootNode = $treeBuilder->getRootNode();

        // Set Configuration
        $rootNode
            ->children()
            ->arrayNode('zone')->scalarPrototype()->end()->defaultValue(['^/api'])->end()
            ->scalarNode('default_accept')->defaultValue('json')->end()
            ->arrayNode('default_groups')->scalarPrototype()->end()->defaultValue(['default'])->end()
            ->arrayNode('allow_accept')->scalarPrototype()->end()->defaultValue(['xml', 'json'])->end()
            ->end();

        return $treeBuilder;
    }
}
