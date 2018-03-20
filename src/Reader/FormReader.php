<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\FormField;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class FormReader extends AbstractReader
{
    protected function getAnnotation(): string
    {
        return FormField::class;
    }
}
