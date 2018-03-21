<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\ExportField;
use KunicMarko\SonataAnnotationBundle\Annotation\ExportFormats;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ExportReader
{
    use AnnotationReaderTrait;

    public function getFields(\ReflectionClass $class): array
    {
        $fields = [];

        foreach ($class->getProperties() as $property) {
            if ($annotation = $this->getPropertyAnnotation($property, ExportField::class)) {
                $fields[$annotation->label ?? $property->getName()] = $property->getName();
            }
        }

        foreach ($class->getMethods() as $method) {
            if ($annotation = $this->getMethodAnnotation($method, ExportField::class)) {
                $fields[$annotation->label ?? $method->getName()] = $method->getName();
            }
        }

        return $fields;
    }

    public function getFormats(\ReflectionClass $class): array
    {
        if ($annotation = $this->getClassAnnotation($class, ExportFormats::class)) {
            return $annotation->formats;
        }

        return [];
    }
}
