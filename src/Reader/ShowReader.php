<?php

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\ShowField;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ShowReader extends AbstractReader
{
    protected function getAnnotation(): string
    {
        return ShowField::class;
    }
}
