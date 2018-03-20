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
        if ($annotation = $this->getClassAnnotation($class, ParentAssociationMapping::class)) {
            return $annotation->parent;
        }

        return null;
    }
}
