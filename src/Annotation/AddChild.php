<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AddChild implements AnnotationInterface
{
    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $field;

    public function getClass(): string
    {
        if ($this->class) {
            return $this->class;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Argument "class" is mandatory in "%s" annotation.',
                self::class
            )
        );
    }

    public function getField(): string
    {
        if ($this->field) {
            return $this->field;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Argument "field" is mandatory in "%s" annotation.',
                self::class
            )
        );
    }
}
