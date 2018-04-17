<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\DependencyInjection\Compiler\AccessCompilerPass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AccessExceptionClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AccessCompilerPassTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp(): void
    {
        $this->container =  new ContainerBuilder();
        $this->container->set('annotation_reader', new AnnotationReader());
        $this->container->setParameter('security.role_hierarchy.roles', []);
    }

    public function testProcess(): void
    {
        $this->initAdminClasses();

        $accessCompilerPass = new AccessCompilerPass();
        $accessCompilerPass->process($this->container);

        $this->assertArrayHasKey('ROLE_VENDOR', $roles = $this->container->getParameter('security.role_hierarchy.roles'));
        $this->assertContains('ROLE_APP_ADMIN_ANNOTATIONCLASS_LIST', $roles['ROLE_VENDOR']);
        $this->assertContains('ROLE_APP_ADMIN_ANNOTATIONEXCEPTIONCLASS_ALL', $roles['ROLE_VENDOR']);
    }

    private function initAdminClasses(
        $classes = [AnnotationClass::class, AnnotationExceptionClass::class, null]
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

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Service "app.admin.%class%" has a parameter "class" as an argument but it is not found.
     */
    public function testProcessExceptionParamNotFound(): void
    {
        $this->initAdminClasses(['%class%']);

        $accessCompilerPass = new AccessCompilerPass();
        $accessCompilerPass->process($this->container);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "role" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\Access" annotation.
     */
    public function testProcessExceptionAnnotation(): void
    {
        $this->initAdminClasses([AccessExceptionClass::class]);

        $accessCompilerPass = new AccessCompilerPass();
        $accessCompilerPass->process($this->container);
    }
}
