<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class DatagridField extends AbstractField
{
    /**
     * @var array
     */
    public $filterOptions = [];

    /**
     * @var string
     */
    public $fieldType;

    /**
     * @var array
     */
    public $fieldOptions = [];

    /**
     * @var int
     */
    public $position;

    public function getPosition(): int
    {
        return $this->position;
    }

    public function hasPosition(): bool
    {
        return null !== $this->position;
    }

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
