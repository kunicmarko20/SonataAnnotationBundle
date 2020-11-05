<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use InvalidArgumentException;
use KunicMarko\SonataAnnotationBundle\Reader\ExportReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ExportReaderTest extends TestCase
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
     * @group legacy
     * @expectedDeprecation The "KunicMarko\SonataAnnotationBundle\Annotation\ParentAssociationMapping" annotation is deprecated since 1.1, to be removed in 2.0. Use KunicMarko\SonataAnnotationBundle\Annotation\AddChild instead.
     */
    public function testGetFieldsAnnotationException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument "field" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\ExportAssociationField" annotation.');

        $this->exportReader->getFields(new \ReflectionClass(AnnotationExceptionClass::class));
    }

    public function testGetFieldsNoAnnotation(): void
    {
        $fields = $this->exportReader->getFields(new \ReflectionClass(EmptyClass::class));

        $this->assertEmpty($fields);
    }
}
