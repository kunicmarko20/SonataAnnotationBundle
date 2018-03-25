<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\FormReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class FormReaderTest extends TestCase
{
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

        $this->formReader->configureCreateFields(
            new \ReflectionClass(AnnotationClass::class),
            $this->formMapper->reveal()
        );
    }

    public function testConfigureEditFieldsAnnotationPresent(): void
    {
        $this->formMapper->add('additionalField', Argument::cetera())->shouldBeCalled();
        $this->formMapper->add('parent', Argument::cetera())->shouldBeCalled();

        $this->formReader->configureEditFields(
            new \ReflectionClass(AnnotationClass::class),
            $this->formMapper->reveal()
        );
    }
}
