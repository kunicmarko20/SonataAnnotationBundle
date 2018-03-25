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
class AutoRegisterCompilerPass implements CompilerPassInterface
{
    private const DEFAULT_SERVICE_PREFIX = 'app.admin.';

    /**
     * @var Reader
     */
    private $annotationReader;

    public function process(ContainerBuilder $container): void
    {
        $this->annotationReader = $container->get('annotation_reader');
        $roles = $container->getParameter('security.role_hierarchy.roles');

        foreach ($this->findFiles($container->getParameter('sonata_annotation.directory')) as $file) {
            $className = $this->getFullyQualifiedClassName($file);

            if (!($annotation = $this->getClassAnnotation($reflection = new \ReflectionClass($className)))) {
                continue;
            }

            $definition = new Definition(
                $annotation->admin,
                [$annotation->code, $className, $annotation->controller]
            );

            $definition->addTag('sonata.admin', $annotation->getTagOptions());

            $container->setDefinition(
                $serviceId = ($annotation->serviceId ?? $this->getServiceId($file)),
                $definition
            );

            if ($permissions = $this->getRoles($reflection, $this->getRolePrefix($serviceId))) {
                $roles = array_merge_recursive($roles, $permissions);
            }
        }

        $container->setParameter('security.role_hierarchy.roles', $roles);
    }

    private function findFiles(string $directory): \IteratorAggregate
    {
        return Finder::create()
            ->in($directory)
            ->files()
            ->name('*.php');
    }

    private function getFullyQualifiedClassName(SplFileInfo $file): string
    {
        return $this->getNamespace($file->getPathname()) . '\\' . $this->getClassName($file->getFilename());
    }

    private function getNamespace(string $filePath): string
    {
        $namespaceLine = preg_grep('/^namespace /', file($filePath));
        preg_match('/namespace (.*);$/', reset($namespaceLine), $match);
        return array_pop($match);
    }

    private function getClassName(string $fileName): string
    {
        return str_replace('.php', '', $fileName);
    }

    private function getClassAnnotation(\ReflectionClass $class): ?Admin
    {
        return $this->annotationReader->getClassAnnotation(
            $class,
            Admin::class
        );
    }

    private function getServiceId(SplFileInfo $file): string
    {
        return self::DEFAULT_SERVICE_PREFIX . $this->getClassName($file->getFilename());
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
