<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\ShowReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass3;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ShowReaderTest extends TestCase
{
    /**
     * @var ShowReader
     */
    private $showReader;
    private $showMapper;

    protected function setUp(): void
    {
        $this->showMapper = $this->prophesize(ShowMapper::class);
        $this->showReader = new ShowReader(new AnnotationReader());
    }

    public function testConfigureFieldsNoAnnotation(): void
    {
        $this->showMapper->add()->shouldNotBeCalled();
        $this->showReader->configureFields(
            new \ReflectionClass(EmptyClass::class),
            $this->showMapper->reveal()
        );
    }

    public function testConfigureFieldsAnnotationPresent(): void
    {
        $this->showMapper->add('field', Argument::cetera())->shouldBeCalled();
        $this->showMapper->add('method', Argument::cetera())->shouldBeCalled();
        $this->showMapper->add('parent.name', Argument::cetera())->shouldBeCalled();

        $this->showReader->configureFields(
            new \ReflectionClass(AnnotationClass::class),
            $this->showMapper->reveal()
        );
    }

    /**
     * @group legacy
     * @expectedDeprecation The "KunicMarko\SonataAnnotationBundle\Annotation\ParentAssociationMapping" annotation is deprecated since 1.1, to be removed in 2.0. Use KunicMarko\SonataAnnotationBundle\Annotation\AddChild instead.
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "field" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\ShowAssociationField" annotation.
     */
    public function testConfigureFieldsAnnotationException(): void
    {
        $this->showReader->configureFields(
            new \ReflectionClass(AnnotationExceptionClass::class),
            $this->showMapper->reveal()
        );
    }

    public function testConfigureCreateFieldsAnnotationPresentPosition(): void
    {
        $mock = $this->createMock(ShowMapper::class);

        $properties = ['parent.name', 'field', 'method'];
        $mock->expects($this->exactly(3))
            ->method('add')
            ->with($this->callback(static function (string $field) use (&$properties): bool {
                $property = array_shift($properties);

                return $field === $property;
            }));

        $this->showReader->configureFields(
            new \ReflectionClass(AnnotationClass::class),
            $mock
        );
    }

    /**
     * @group legacy
     */
    public function testPositionShouldBeUnique(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Position "1" is already in use by "field", try setting a different position for "field2".');
        $this->showReader->configureFields(
            new \ReflectionClass(AnnotationExceptionClass3::class),
            $this->showMapper->reveal()
        );
    }
}
