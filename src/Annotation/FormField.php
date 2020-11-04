<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class FormField extends AbstractField
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

    /**
     * @var integer
     */
    public $position;

    public function getSettings(): array
    {
        return [
            $this->type,
            $this->options,
            $this->fieldDescriptionOptions
        ];
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function hasPosition(): bool
    {
        return $this->position !== null;
    }
}
