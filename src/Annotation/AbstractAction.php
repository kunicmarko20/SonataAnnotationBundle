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
}
