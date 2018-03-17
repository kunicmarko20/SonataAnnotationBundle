<?php

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
abstract class AbstractField
{
    public $type;
    public $fieldDescriptionOptions = [];

    public function getSettings(): array
    {
        return [
            $this->type,
            $this->fieldDescriptionOptions
        ];
    }
}
