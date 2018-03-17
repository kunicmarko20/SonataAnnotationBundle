<?php

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class FormField extends AbstractField
{
    public $options = [];

    public function getSettings(): array
    {
        return [
            $this->type,
            $this->options,
            $this->fieldDescriptionOptions
        ];
    }
}
