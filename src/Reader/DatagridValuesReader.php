<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\DatagridValues;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class DatagridValuesReader
{
    use AnnotationReaderTrait;

    public function getDatagridValues(\ReflectionClass $class): array
    {
        if ($annotation = $this->getClassAnnotation($class, DatagridValues::class)) {
            return $annotation->values;
        }

        return [];
    }
}
