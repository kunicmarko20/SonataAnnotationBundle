<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\AnnotationReader;
use InvalidArgumentException;
use KunicMarko\SonataAnnotationBundle\DependencyInjection\Compiler\AddChildCompilerPass;
use KunicMarko\SonataAnnotationBundle\Reader\AddChildReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AddChildCompilerPassTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp(): void
    {
        $this->container =  new ContainerBuilder();
        $this->container->set('sonata.annotation.reader.add_child', new AddChildReader(new AnnotationReader()));
    }

    public function testProcess(): void
    {
        $this->initAdminClasses();

        $accessCompilerPass = new AddChildCompilerPass();
        $accessCompilerPass->process($this->container);

        $calls = $this->container->getDefinition('app.admin.AnnotationClass')->getMethodCalls();

        $this->assertContains('addChild', $calls[0][0]);
        $this->assertContains('test', $calls[0][1][1]);
    }

    private function initAdminClasses(
        $classes = [AnnotationClass::class, EmptyClass::class, null]
    ) {
        foreach ($classes as $key => $class) {
            $definition = new Definition(
                'test',
                [null, $class, null]
            );

            $definition->addTag('sonata.admin', []);

            $className = explode("\\", $class ?? '');

            $this->container->setDefinition(
                'app.admin.' . end($className),
                $definition
            );
        }
    }

    public function testProcessExceptionParamNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass is missing Admin Class.');

        $this->initAdminClasses([AnnotationClass::class]);

        $accessCompilerPass = new AddChildCompilerPass();
        $accessCompilerPass->process($this->container);
    }
}
