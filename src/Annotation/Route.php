<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class Route implements AnnotationInterface
{
    public const ID_PARAMETER = '{id}';
    private const DEFAULT_METHOD = 'add';

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $method = self::DEFAULT_METHOD;

    public function shouldAddRoute(): bool
    {
        return $this->method === self::DEFAULT_METHOD;
    }
}
