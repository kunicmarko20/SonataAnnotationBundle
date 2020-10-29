<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use InvalidArgumentException;
use KunicMarko\SonataAnnotationBundle\Reader\AddChildReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass2;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AddChildReaderTest extends TestCase
{
    /**
     * @var AddChildReader
     */
    private $addChildReader;

    protected function setUp(): void
    {
        $this->addChildReader = new AddChildReader(new AnnotationReader());
    }

    public function testGetChildrenNoAnnotation(): void
    {
        $children = $this->addChildReader->getChildren(
            new \ReflectionClass(EmptyClass::class)
        );

        $this->assertEmpty($children);
    }

    public function testGetChildrenAnnotationPresent(): void
    {
        $children = $this->addChildReader->getChildren(
            new \ReflectionClass(AnnotationClass::class)
        );

        $this->assertSame(EmptyClass::class, key($children));
        $this->assertSame('test', reset($children));
    }

    public function testGetChildrenAnnotationException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument "class" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\AddChild" annotation.');

        $this->addChildReader->getChildren(
            new \ReflectionClass(AnnotationExceptionClass::class)
        );
    }

    public function testGetChildrenAnnotationException2(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument "field" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\AddChild" annotation.');

        $this->addChildReader->getChildren(
            new \ReflectionClass(AnnotationExceptionClass2::class)
        );
    }
}
