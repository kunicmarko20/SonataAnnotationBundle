<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\DependencyInjection\Compiler;

use KunicMarko\SonataAnnotationBundle\Reader\ChildAdminReader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AddChildrenCompilerPass implements CompilerPassInterface
{
    use FindClassTrait;

    public function process(ContainerBuilder $container): void
    {
        /** @var ChildAdminReader $annotationReader */
        $annotationReader = $container->get('sonata.annotation.reader.child_admin');
        /** @var string[] $classAdmins */
        $classAdmins = [];
        /** @var array[] $adminParents */
        $adminParents = [];

        foreach ($container->findTaggedServiceIds('sonata.admin') as $id => $tag) {
            if (!($class = $this->getClass($container, $id))) {
                continue;
            }

            $classAdmins[$class] = $id;

            if ($parents = $annotationReader->getChildren(new \ReflectionClass($class))) {
                $adminParents[$id] = $parents;
            }
        }

        foreach ($adminParents as $id => $parents) {
            foreach ($parents as $parent => $field) {
                if (!isset($classAdmins[$parent])) {
                    throw new \InvalidArgumentException(sprintf(
                        '%s is missing Admin Class.',
                        $parent
                    ));
                }

                $definition = $container->getDefinition($classAdmins[$parent]);
                $definition->addMethodCall('addChild'. [$id, $field]);
            }
        }
    }
}
