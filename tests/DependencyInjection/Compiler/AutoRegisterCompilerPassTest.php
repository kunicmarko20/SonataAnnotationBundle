<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Admin\AnnotationAdmin;
use KunicMarko\SonataAnnotationBundle\DependencyInjection\Compiler\AutoRegisterCompilerPass;
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
        $this->container->set('annotation_reader', new AnnotationReader());
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
