<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class FormField extends AbstractField
{
    /**
     * @var array
     */
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
