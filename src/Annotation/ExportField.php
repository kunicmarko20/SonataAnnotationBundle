<?php

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ExportField
{
    public $label;
}
