<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
abstract class AbstractAction implements AnnotationInterface
{
    /**
     * @var string
     */
    public $template;

    public function getTemplate(): string
    {
        if ($this->template) {
            return $this->template;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Argument "template" is mandatory in "%s" annotation.',
                static::class
            )
        );
    }
}
