<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class FormField extends AbstractField
{
    public const ACTION_CREATE = 'create';
    public const ACTION_EDIT = 'edit';

    /**
     * @var string
     */
    public $action;

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
