<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use Doctrine\Common\Annotations\Reader;
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
        $properties = [];

        foreach ($class->getProperties() as $property) {
            if ($annotation = $this->getPropertyAnnotation($property, ExportField::class)) {
                $properties[$annotation->label ?? $property->getName()] = $property->getName();
            }
        }

        return $properties;
    }

    public function getFormats(\ReflectionClass $class): array
    {
        if ($annotation = $this->getClassAnnotation($class, ExportFormats::class)) {
            return $annotation->formats;
        }

        return [];
    }
}
