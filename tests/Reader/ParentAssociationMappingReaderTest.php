<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\ParentAssociationMappingReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ParentAssociationMappingReaderTest extends TestCase
{
    /**
     * @var ParentAssociationMappingReader
     */
    private $parentAssociationMappingReader;

    protected function setUp(): void
    {
        $this->parentAssociationMappingReader = new ParentAssociationMappingReader(new AnnotationReader());
    }

    public function testGetParentNoAnnotation(): void
    {
        $parent = $this->parentAssociationMappingReader->getParent(new \ReflectionClass(EmptyClass::class));

        $this->assertNull($parent);
    }
}
