<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @internal
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
trait FindClassTrait
{
    private function getClass(ContainerBuilder $container, string $id): ?string
    {
        $definition = $container->getDefinition($id);

        //Entity can be a class or a parameter
        $class = $definition->getArgument(1); //1 is Entity

        if ($class[0] !== '%') {
            return $class;
        }

        if ($container->hasParameter($class = trim($class, '%'))) {
            return $container->getParameter($class);
        }

        throw new \LogicException(sprintf(
            'Service "%s" has a parameter "%s" as an argument but it is not found.',
            $id,
            $class
        ));
    }
}
