<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\DatagridValuesReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class DatagridValuesReaderTest extends TestCase
{
    /**
     * @var DatagridValuesReader
     */
    private $datagridValuesReader;

    protected function setUp(): void
    {
        $this->datagridValuesReader = new DatagridValuesReader(new AnnotationReader());
    }

    public function testGetFormatsAnnotationPresent(): void
    {
        $values = $this->datagridValuesReader->getDatagridValues(new \ReflectionClass(AnnotationClass::class));

        $this->assertSame(['test' => 'value'], $values);
    }

    public function testGetFormatsNoAnnotation(): void
    {
        $values = $this->datagridValuesReader->getDatagridValues(new \ReflectionClass(EmptyClass::class));

        $this->assertEmpty($values);
    }
}
