<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sonata_annotation');

        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('sonata_annotation');
        }

        $rootNode
            ->children()
                ->scalarNode('directory')->defaultNull()->end()
            ->end();

        return $treeBuilder;
    }
}
