<?php

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ListField extends AbstractField
{
    public $identifier = false;
}
