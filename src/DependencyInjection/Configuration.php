<?php

namespace BenTools\CrontabBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('bentools_crontab');

        $rootNode
            ->children()
                ->scalarNode('dist_file')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
