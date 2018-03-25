<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\RouteReader;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\AnnotationExceptionClass;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\AnnotationExceptionClass2;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class RouteReaderTest extends TestCase
{
    /**
     * @var RouteReader
     */
    private $routeReader;

    protected function setUp(): void
    {
        $this->routeReader = new RouteReader(new AnnotationReader());
    }

    public function testGetRoutesNoAnnotation(): void
    {
        [$addRoutes, $removeRoutes] = $this->routeReader->getRoutes(
            new \ReflectionClass(EmptyClass::class)
        );

        $this->assertEmpty($addRoutes);
        $this->assertEmpty($removeRoutes);
    }

    public function testGetRoutesAnnotationPresent(): void
    {
        [$addRoutes, $removeRoutes] = $this->routeReader->getRoutes(
            new \ReflectionClass(AnnotationClass::class)
        );

        $this->assertSame('import', $addRoutes['import']->getName());
        $this->assertNull($addRoutes['import']->path);
        $this->assertSame('{id}/send_mail', $addRoutes['send_mail']->path);
        $this->assertSame('edit', $removeRoutes['edit']->getName());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "name" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\AddRoute" annotation.
     */
    public function testGetRoutesAnnotationException(): void
    {
        $this->routeReader->getRoutes(
            new \ReflectionClass(AnnotationExceptionClass::class)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "name" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\RemoveRoute" annotation.
     */
    public function testGetRoutesAnnotationException2(): void
    {
        $this->routeReader->getRoutes(
            new \ReflectionClass(AnnotationExceptionClass2::class)
        );
    }
}
