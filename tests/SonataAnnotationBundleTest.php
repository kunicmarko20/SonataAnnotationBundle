<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests;

use KunicMarko\SonataAnnotationBundle\DependencyInjection\Compiler\AutoRegisterCompilerPass;
use KunicMarko\SonataAnnotationBundle\SonataAnnotationBundle;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class SonataAnnotationBundleTest extends TestCase
{
    /**
     * @var SonataAnnotationBundle
     */
    private $bundle;

    protected function setUp()
    {
        $this->bundle = new SonataAnnotationBundle();
    }

    public function testBundle(): void
    {
        $this->assertInstanceOf(Bundle::class, $this->bundle);
    }

    public function testCompilerPasses(): void
    {
        $containerBuilder = $this->prophesize(ContainerBuilder::class);

        $containerBuilder->addCompilerPass(
            Argument::type(AutoRegisterCompilerPass::class),
            Argument::cetera()
        )->shouldBeCalledTimes(1);

        $this->bundle->build($containerBuilder->reveal());
    }
}
