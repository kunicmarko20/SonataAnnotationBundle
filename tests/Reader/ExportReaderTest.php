<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\ExportReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ExportReaderTest extends TestCase
{
    /**
     * @var ExportReader
     */
    private $exportReader;

    protected function setUp(): void
    {
        $this->exportReader = new ExportReader(new AnnotationReader());
    }

    public function testGetFormatsNoAnnotation(): void
    {
        $formats = $this->exportReader->getFormats(new \ReflectionClass(EmptyClass::class));

        $this->assertEmpty($formats);
    }

    public function testGetFieldsAnnotationPresent(): void
    {
        $fields = $this->exportReader->getFields(new \ReflectionClass(AnnotationClass::class));

        $this->assertCount(4, $fields);
        $this->assertSame('field', $fields['field']);
        $this->assertSame('additionalField', $fields['label']);
        $this->assertSame('method', $fields['method']);
        $this->assertSame('parent.name', $fields['parent.name']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "field" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\ExportAssociationField" annotation.
     */
    public function testGetFieldsAnnotationException(): void
    {
        $this->exportReader->getFields(new \ReflectionClass(AnnotationExceptionClass::class));
    }

    public function testGetFieldsNoAnnotation(): void
    {
        $fields = $this->exportReader->getFields(new \ReflectionClass(EmptyClass::class));

        $this->assertEmpty($fields);
    }
}
