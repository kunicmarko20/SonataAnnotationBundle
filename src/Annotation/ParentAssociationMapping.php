<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ParentAssociationMapping implements AnnotationInterface
{
    public function __construct()
    {
        @trigger_error(
            sprintf(
                'The "%s" annotation is deprecated since 1.1, to be removed in 2.0. Use %s instead.',
                self::class,
                AddChild::class
            ),
            E_USER_DEPRECATED
        );
    }
}
