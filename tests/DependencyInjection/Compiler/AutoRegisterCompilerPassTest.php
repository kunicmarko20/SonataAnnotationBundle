<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Admin\AnnotationAdmin;
use KunicMarko\SonataAnnotationBundle\DependencyInjection\Compiler\AutoRegisterCompilerPass;
use KunicMarko\SonataAnnotationBundle\Reader\ActionButtonReader;
use KunicMarko\SonataAnnotationBundle\Reader\DashboardActionReader;
use KunicMarko\SonataAnnotationBundle\Reader\DatagridReader;
use KunicMarko\SonataAnnotationBundle\Reader\ExportReader;
use KunicMarko\SonataAnnotationBundle\Reader\FormReader;
use KunicMarko\SonataAnnotationBundle\Reader\ListReader;
use KunicMarko\SonataAnnotationBundle\Reader\RouteReader;
use KunicMarko\SonataAnnotationBundle\Reader\ShowReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AutoRegisterCompilerPassTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp(): void
    {
        $this->container =  new ContainerBuilder();
        $annotationReader = new AnnotationReader();
        $this->container->set('annotation_reader', $annotationReader);
        $this->container->set('sonata.annotation.reader.form', new FormReader($annotationReader));
        $this->container->set('sonata.annotation.reader.list', new ListReader($annotationReader));
        $this->container->set('sonata.annotation.reader.show', new ShowReader($annotationReader));
        $this->container->set('sonata.annotation.reader.datagrid', new DatagridReader($annotationReader));
        $this->container->set('sonata.annotation.reader.route', new RouteReader($annotationReader));
        $this->container->set('sonata.annotation.reader.action_button', new ActionButtonReader($annotationReader));
        $this->container->set('sonata.annotation.reader.dashboard_action', new DashboardActionReader($annotationReader));
        $this->container->set('sonata.annotation.reader.export', new ExportReader($annotationReader));
        $this->container->setParameter('sonata_annotation.directory', __DIR__ . '/../../Fixtures');
    }

    /**
     * @dataProvider processData
     */
    public function testProcess(string $serviceId, string $class, ?string $label): void
    {
        $autoRegisterCompilerPass = new AutoRegisterCompilerPass();

        $autoRegisterCompilerPass->process($this->container);

        $this->assertTrue($this->container->hasDefinition($serviceId));

        $admin = $this->container->getDefinition($serviceId);

        $this->assertSame(AnnotationAdmin::class, $admin->getClass());
        $this->assertContains($class, $admin->getArguments());
        $this->assertTrue($admin->hasTag('sonata.admin'));
        $attributes = $admin->getTag('sonata.admin');

        $this->assertSame('orm', $attributes[0]['manager_type']);
        $this->assertSame($label, $attributes[0]['label']);

        $this->assertCount(2, $this->container->findTaggedServiceIds('sonata.admin'));
    }

    public function processData(): array
    {
        return [
            [
                'app.admin.AnnotationClass',
                AnnotationClass::class,
                'Test',
            ],
            [
                'test.the.id',
                AnnotationExceptionClass::class,
                null,
            ],
        ];
    }
}
