<?php

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class DatagridField extends AbstractField
{
    public $filterOptions = [];
    public $fieldType;
    public $fieldOptions;

    public function getSettings(): array
    {
        return [
            $this->type,
            $this->filterOptions,
            $this->fieldType,
            $this->fieldOptions,
            $this->fieldDescriptionOptions
        ];
    }
}
