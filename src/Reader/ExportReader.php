<?php

namespace KunicMarko\SonataAnnotationBundle\Reader;

use Doctrine\Common\Annotations\Reader;
use KunicMarko\SonataAnnotationBundle\Annotation\ExportField;
use KunicMarko\SonataAnnotationBundle\Annotation\ExportFormats;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ExportReader
{
    protected $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function getFields(\ReflectionClass $entity): array
    {
        $properties = [];

        foreach ($entity->getProperties() as $property) {
            if ($annotation = $this->annotationReader->getPropertyAnnotation($property, ExportField::class)) {
                $properties[$annotation->label ?? $property->getName()] = $property->getName();
            }
        }

        return $properties;
    }

    public function getFormats(\ReflectionClass $entity): array
    {
        if ($annotation = $this->annotationReader->getClassAnnotation($entity, ExportFormats::class)) {
            return $annotation->formats;
        }

        return [];
    }
}
