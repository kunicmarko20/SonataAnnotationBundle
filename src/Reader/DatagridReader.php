<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\DatagridField;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class DatagridReader extends AbstractReader
{
    protected function getAnnotation(): string
    {
        return DatagridField::class;
    }
}
