<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use InvalidArgumentException;
use KunicMarko\SonataAnnotationBundle\Reader\ListReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass2;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass4;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ListReaderTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var ListReader
     */
    private $listReader;
    private $listMapper;

    protected function setUp(): void
    {
        $this->listMapper = $this->prophesize(ListMapper::class);
        $this->listReader = new ListReader(new AnnotationReader());
    }

    public function testConfigureFieldsNoAnnotation(): void
    {
        $this->listMapper->add()->shouldNotBeCalled();
        $this->listReader->configureFields(
            new \ReflectionClass(EmptyClass::class),
            $this->listMapper->reveal()
        );
    }

    public function testConfigureFieldsAnnotationPresent(): void
    {
        $this->listMapper->addIdentifier('field', Argument::cetera())->shouldBeCalled();
        $this->listMapper->add('method', Argument::cetera())->shouldBeCalled();
        $this->listMapper->add('parent.name', Argument::cetera())->shouldBeCalled();
        $this->listMapper->add('additionalField', Argument::cetera())->shouldBeCalled();
        $this->listMapper->add(
            '_action',
            Argument::any(),
            [
                'actions' => [
                    'edit' => null,
                    'delete' => null,
                ],
            ]
        )->shouldBeCalled();

        $this->listReader->configureFields(
            new \ReflectionClass(AnnotationClass::class),
            $this->listMapper->reveal()
        );
    }

    /**
     * @group legacy
     * @expectedDeprecation The "KunicMarko\SonataAnnotationBundle\Annotation\ParentAssociationMapping" annotation is deprecated since 1.1, to be removed in 2.0. Use KunicMarko\SonataAnnotationBundle\Annotation\AddChild instead.
     */
    public function testConfigureFieldsAnnotationException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument "field" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\ListAssociationField" annotation.');

        $this->listReader->configureFields(
            new \ReflectionClass(AnnotationExceptionClass::class),
            $this->listMapper->reveal()
        );
    }

    public function testConfigureFieldsAnnotationActionException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument "name" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\ListAction" annotation.');

        $this->listReader->configureFields(
            new \ReflectionClass(AnnotationExceptionClass2::class),
            $this->listMapper->reveal()
        );
    }

    public function testConfigureFieldsAnnotationPresentPosition(): void
    {
        //
        // Without identifier
        //

        $mock = $this->createMock(ListMapper::class);

        $propertiesAndMethods = ['parent.name', 'additionalField', 'method', '_action'];
        $mock->expects($this->exactly(4))
            ->method('add')
            ->with($this->callback(static function(string $field) use (&$propertiesAndMethods): bool {
                $propertyAndMethod = array_shift($propertiesAndMethods);

                return $field === $propertyAndMethod;
            }));

        $this->listReader->configureFields(
            new \ReflectionClass(AnnotationClass::class),
            $mock
        );

        //
        // Identifier
        //

        $mock = $this->createMock(ListMapper::class);

        $propertiesAndMethods = ['field'];
        $mock->expects($this->exactly(1))
            ->method('addIdentifier')
            ->with($this->callback(static function(string $field) use (&$propertiesAndMethods): bool {
                $propertyAndMethod = array_shift($propertiesAndMethods);

                return $field === $propertyAndMethod;
            }));

        $this->listReader->configureFields(
            new \ReflectionClass(AnnotationClass::class),
            $mock
        );
    }

    /**
     * @group legacy
     */
    public function testPositionShouldBeUnique(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Position "1" is already in use by "field", try setting a different position for "field2".');
        $this->listReader->configureFields(
            new \ReflectionClass(AnnotationExceptionClass4::class),
            $this->listMapper->reveal()
        );
    }
}
