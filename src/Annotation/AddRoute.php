<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class AddRoute implements AnnotationInterface
{
    public const ID_PARAMETER = '{id}';

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $path;
}
