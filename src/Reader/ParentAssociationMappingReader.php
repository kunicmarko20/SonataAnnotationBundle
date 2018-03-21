<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\ParentAssociationMapping;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ParentAssociationMappingReader
{
    use AnnotationReaderTrait;

    public function getParent(\ReflectionClass $class): ?string
    {
        foreach ($class->getProperties() as $property) {
            if ($this->getPropertyAnnotation($property, ParentAssociationMapping::class)) {
                return $property->getName();
            }
        }

        return null;
    }
}
