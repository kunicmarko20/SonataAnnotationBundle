<?php

namespace KunicMarko\SonataAnnotationBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class SonataAnnotationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'sonata_annotation.directory',
            $config['directory'] ?? $container->getParameter('kernel.root_dir')
        );

        $container->setParameter(
            'sonata_annotation.admin',
            $config['admin']
        );

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('reader.xml');
    }
}
