<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\ExportReader;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\EmptyClass;
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

    public function testGetFormatsAnnotationPresent(): void
    {
        $formats = $this->exportReader->getFormats(new \ReflectionClass(AnnotationClass::class));

        $this->assertSame(['json', 'xml'], $formats);
    }

    public function testGetFormatsNoAnnotation(): void
    {
        $formats = $this->exportReader->getFormats(new \ReflectionClass(EmptyClass::class));

        $this->assertEmpty($formats);
    }

    public function testGetFieldsAnnotationPresent(): void
    {
        $fields = $this->exportReader->getFields(new \ReflectionClass(AnnotationClass::class));

        $this->assertCount(3, $fields);
        $this->assertSame('field', $fields['field']);
        $this->assertSame('additionalField', $fields['label']);
        $this->assertSame('method', $fields['method']);
    }

    public function testGetFieldsNoAnnotation(): void
    {
        $fields = $this->exportReader->getFields(new \ReflectionClass(EmptyClass::class));

        $this->assertEmpty($fields);
    }
}
