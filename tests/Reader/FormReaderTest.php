<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\FormReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class FormReaderTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var FormReader
     */
    private $formReader;
    private $formMapper;

    protected function setUp(): void
    {
        $this->formMapper = $this->prophesize(FormMapper::class);
        $this->formReader = new FormReader(new AnnotationReader());
    }

    public function testConfigureCreateFieldsNoAnnotation(): void
    {
        $this->formMapper->add()->shouldNotBeCalled();
        $this->formReader->configureCreateFields(
            new \ReflectionClass(EmptyClass::class),
            $this->formMapper->reveal()
        );
    }

    public function testConfigureCreateFieldsAnnotationPresent(): void
    {
        $this->formMapper->add('field', Argument::cetera())->shouldBeCalled();
        $this->formMapper->add('parent', Argument::cetera())->shouldBeCalled();
        $this->formMapper->add('additionalField2', Argument::cetera())->shouldBeCalled();

        $this->formReader->configureCreateFields(
            new \ReflectionClass(AnnotationClass::class),
            $this->formMapper->reveal()
        );
    }

    public function testConfigureEditFieldsAnnotationPresent(): void
    {
        $this->formMapper->add('additionalField', Argument::cetera())->shouldBeCalled();
        $this->formMapper->add('parent', Argument::cetera())->shouldBeCalled();
        $this->formMapper->add('additionalField2', Argument::cetera())->shouldBeCalled();

        $this->formReader->configureEditFields(
            new \ReflectionClass(AnnotationClass::class),
            $this->formMapper->reveal()
        );
    }

    public function testConfigureCreateFieldsAnnotationPresentPosition(): void
    {
        $mock = $this->createMock(FormMapper::class);

        $properties = ['parent', 'field', 'additionalField2'];
        $mock->expects($this->exactly(3))
            ->method('add')
            ->with($this->callback(static function(string $field) use (&$properties): bool {
                $property = array_shift($properties);
                return $field === $property;
            }));

        $this->formReader->configureCreateFields(
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
        $this->formReader->configureCreateFields(
            new \ReflectionClass(AnnotationExceptionClass::class),
            $this->formMapper->reveal()
        );
    }
}
