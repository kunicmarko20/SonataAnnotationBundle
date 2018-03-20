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
class AutoRegisterPass implements CompilerPassInterface
{
    private const DEFAULT_SERVICE_PREFIX = 'app.admin.';

    public function process(ContainerBuilder $container): void
    {
        /** @var Reader $annotationReader */
        $annotationReader = $container->get('annotation_reader');

        foreach ($this->findFiles($container->getParameter('sonata_annotation.directory')) as $file) {
            $className = $this->getFullyQualifiedClassName($file);

            if ($annotation = $this->getAnnotation($annotationReader, $className)) {
                $definition = new Definition(
                    $annotation->admin,
                    [$annotation->code, $className, $annotation->controller]
                );

                $definition->addTag('sonata.admin', $annotation->getTagOptions());

                $container->setDefinition(
                    $annotation->serviceId ?? self::DEFAULT_SERVICE_PREFIX . $this->getClassName($file->getFilename()),
                    $definition
                );
            }
        }
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

    private function getAnnotation(Reader $annotationReader, string $fullyQualifiedClassName): ?Admin
    {
        return $annotationReader->getClassAnnotation(
            new \ReflectionClass($fullyQualifiedClassName),
            Admin::class
        );
    }
}
