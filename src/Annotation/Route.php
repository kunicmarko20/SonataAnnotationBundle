<?php

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class Route
{
    private const DEFAULT_OPTION = 'add';

    public $name;
    public $path;
    public $option = self::DEFAULT_OPTION;

    public function getArgumentsByOption(): array
    {
        if ($this->option === self::DEFAULT_OPTION) {
            return [$this->name, $this->path];
        }

        return [$this->name];
    }
}
