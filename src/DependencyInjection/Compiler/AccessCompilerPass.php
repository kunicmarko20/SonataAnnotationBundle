<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\Reader;
use KunicMarko\SonataAnnotationBundle\Annotation\Access;
use KunicMarko\SonataAnnotationBundle\Annotation\Admin;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AccessCompilerPass implements CompilerPassInterface
{
    private const ENTITY_ARGUMENT_IN_SERVICE_DEFINITION = 1;

    /**
     * @var Reader
     */
    private $annotationReader;

    public function process(ContainerBuilder $container): void
    {
        $this->annotationReader = $container->get('annotation_reader');
        $roles = $container->getParameter('security.role_hierarchy.roles');

        foreach ($container->findTaggedServiceIds('sonata.admin') as $id => $tag) {
            if (!($class = $this->getClass($container, $id))) {
                continue;
            }

            if ($permissions = $this->getRoles(new \ReflectionClass($class), $this->getRolePrefix($id))) {
                $roles = array_merge_recursive($roles, $permissions);
            }
        }

        $container->setParameter('security.role_hierarchy.roles', $roles);
    }

    private function getClass(ContainerBuilder $container, string $id): ?string
    {
        $definition = $container->getDefinition($id);

        //Entity can be a class or a parameter
        $class = $definition->getArgument(self::ENTITY_ARGUMENT_IN_SERVICE_DEFINITION);

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

    private function getRolePrefix(string $serviceId): string
    {
        return 'ROLE_' . str_replace('.', '_', strtoupper($serviceId)) . '_';
    }

    private function getRoles(\ReflectionClass $class, string $prefix): array
    {
        $roles = [];

        foreach ($this->annotationReader->getClassAnnotations($class) as $annotation) {
            if (!$annotation instanceof Access) {
                continue;
            }

            $roles[$annotation->getRole()] = array_map(
                function (string $permission) use ($prefix) {
                    return $prefix . strtoupper($permission);
                },
                $annotation->permissions
            );
        }

        return $roles;
    }
}
