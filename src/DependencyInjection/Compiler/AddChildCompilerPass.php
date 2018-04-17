<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\DependencyInjection\Compiler;

use KunicMarko\SonataAnnotationBundle\Reader\AddChildReader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AddChildCompilerPass implements CompilerPassInterface
{
    use FindClassTrait;

    public function process(ContainerBuilder $container): void
    {
        /** @var AddChildReader $annotationReader */
        $annotationReader = $container->get('sonata.annotation.reader.add_child');
        /** @var string[] $admins */
        $admins = [];
        /** @var array[] $adminChildren */
        $adminChildren = [];

        foreach ($container->findTaggedServiceIds('sonata.admin') as $id => $tag) {
            if (!($class = $this->getClass($container, $id))) {
                continue;
            }

            $admins[$class] = $id;

            if ($children = $annotationReader->getChildren(new \ReflectionClass($class))) {
                $adminChildren[$id] = $children;
            }
        }

        foreach ($adminChildren as $id => $children) {
            foreach ($children as $class => $field) {
                if (!isset($admins[$class])) {
                    throw new \InvalidArgumentException(sprintf(
                        '%s is missing Admin Class.',
                        $class
                    ));
                }

                $definition = $container->getDefinition($id);
                $definition->addMethodCall('addChild', [$container->getDefinition($admins[$class]), $field]);
            }
        }
    }
}
