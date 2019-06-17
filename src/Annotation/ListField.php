<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ListField extends AbstractField
{
    /**
     * @var bool
     */
    public $identifier = false;

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
}
