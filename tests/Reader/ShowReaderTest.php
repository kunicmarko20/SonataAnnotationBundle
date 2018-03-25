<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\ShowReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass;
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
}
