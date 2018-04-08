<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\DependencyInjection;

use KunicMarko\SonataAnnotationBundle\DependencyInjection\SonataAnnotationExtension;
use KunicMarko\SonataAnnotationBundle\Reader\ListReader;
use KunicMarko\SonataAnnotationBundle\Reader\RouteReader;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class SonataAnnotationExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadsFormServiceDefinition(): void
    {
        $this->container->setParameter('kernel.project_dir', $param = 'test');

        $this->load();

        $this->assertContainerBuilderHasService(
            'sonata.annotation.reader.route',
            RouteReader::class
        );
        $this->assertContainerBuilderHasService(
            'sonata.annotation.reader.list',
            ListReader::class
        );

        $this->assertContainerBuilderHasParameter(
            'sonata_annotation.directory',
            $param . '/src/'
        );
    }

    protected function getContainerExtensions(): array
    {
        return [new SonataAnnotationExtension()];
    }
}