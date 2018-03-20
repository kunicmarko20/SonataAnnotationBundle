<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
abstract class AbstractField implements AnnotationInterface
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    public $fieldDescriptionOptions = [];

    public function getSettings(): array
    {
        return [
            $this->type,
            $this->fieldDescriptionOptions
        ];
    }
}
