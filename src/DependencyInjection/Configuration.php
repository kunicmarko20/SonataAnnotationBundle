<?php

namespace KunicMarko\SonataAnnotationBundle\DependencyInjection;

use KunicMarko\SonataAnnotationBundle\Admin\Admin;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sonata_annotation');

        $rootNode
            ->children()
                ->scalarNode('directory')->defaultNull()->end()
                ->scalarNode('admin')->defaultValue(Admin::class)->end()
            ->end();

        return $treeBuilder;
    }
}
