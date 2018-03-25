<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\ListReader;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\AnnotationExceptionClass;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\AnnotationExceptionClass2;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ListReaderTest extends TestCase
{
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
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "field" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\ListAssociationField" annotation.
     */
    public function testConfigureFieldsAnnotationException(): void
    {
        $this->listReader->configureFields(
            new \ReflectionClass(AnnotationExceptionClass::class),
            $this->listMapper->reveal()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "name" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\ListAction" annotation.
     */
    public function testConfigureFieldsAnnotationActionException(): void
    {
        $this->listReader->configureFields(
            new \ReflectionClass(AnnotationExceptionClass2::class),
            $this->listMapper->reveal()
        );
    }
}
