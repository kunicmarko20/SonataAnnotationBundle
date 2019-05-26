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

    /**
     * @var string
     */
    public $group;

    /**
     * @var string
     */
    public $tab;

    public function getSettings(): array
    {
        return [
            $this->type,
            $this->options,
            $this->fieldDescriptionOptions
        ];
    }

    public function hasGroup(): bool
    {
        return $this->group !== null;
    }

    public function hasTab(): bool
    {
        return $this->tab !== null;
    }

    public function getGroup(): ?string
    {
        if ($this->tab && $this->group) {
            throw new \InvalidArgumentException('You can set either group or tab, not both.');
        }

        return $this->group !== "" ? $this->group : null;
    }

    public function getTab(): ?string
    {
        if ($this->tab && $this->group) {
            throw new \InvalidArgumentException('You can set either group or tab, not both.');
        }

        return $this->tab !== "" ? $this->tab : null;
    }
}
