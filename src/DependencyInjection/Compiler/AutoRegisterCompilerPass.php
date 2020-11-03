<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\Reader;
use KunicMarko\SonataAnnotationBundle\Annotation\Admin;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AutoRegisterCompilerPass implements CompilerPassInterface
{
    private const DEFAULT_SERVICE_PREFIX = 'app.admin.';

    /**
     * @var Reader
     */
    private $annotationReader;

    public function process(ContainerBuilder $container): void
    {
        $this->annotationReader = $container->get('annotation_reader');
        $formReader = $container->get('sonata.annotation.reader.form');
        $listReader = $container->get('sonata.annotation.reader.list');
        $showReader = $container->get('sonata.annotation.reader.show');
        $datagridReader = $container->get('sonata.annotation.reader.datagrid');
        $routeReader = $container->get('sonata.annotation.reader.route');
        $actionButtonReader = $container->get('sonata.annotation.reader.action_button');
        $dashboardActionReader = $container->get('sonata.annotation.reader.dashboard_action');
        $exportReader = $container->get('sonata.annotation.reader.export');

        foreach ($this->findFiles($container->getParameter('sonata_annotation.directory')) as $file) {
            if (!($className = $this->getFullyQualifiedClassName($file))) {
                continue;
            }

            if (!\class_exists($className)) {
                continue;
            }

            if (!($annotation = $this->getClassAnnotation($reflection = new \ReflectionClass($className)))) {
                continue;
            }

            $definition = new Definition(
                $annotation->admin,
                [
                    /**
                     * 'sonata.annotation.reader.form',
                    'sonata.annotation.reader.list',
                    'sonata.annotation.reader.show',
                    'sonata.annotation.reader.datagrid',
                    'sonata.annotation.reader.route',
                    'sonata.annotation.reader.action_button',
                    'sonata.annotation.reader.dashboard_action',
                    'sonata.annotation.reader.export',
                     */
                    $formReader,
                    $listReader,
                    $showReader,
                    $datagridReader,
                    $routeReader,
                    $actionButtonReader,
                    $dashboardActionReader,
                    $exportReader,
                    $annotation->code,
                    $className,
                    $annotation->controller
                ]
            );

            $definition->addTag('sonata.admin', $annotation->getTagOptions());

            $container->setDefinition(
                $serviceId = ($annotation->serviceId ?? $this->getServiceId($file)),
                $definition
            );
        }
    }

    private function findFiles(string $directory): \IteratorAggregate
    {
        return Finder::create()
            ->in($directory)
            ->files()
            ->name('*.php');
    }

    private function getFullyQualifiedClassName(SplFileInfo $file): ?string
    {
        if (!($namespace = $this->getNamespace($file->getPathname()))) {
            return null;
        }

        return $namespace . '\\' . $this->getClassName($file->getFilename());
    }

    private function getNamespace(string $filePath): ?string
    {
        $namespaceLine = preg_grep('/^namespace /', file($filePath));

        if (!$namespaceLine) {
            return null;
        }

        preg_match('/namespace (.*);$/', trim(reset($namespaceLine)), $match);

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
}
