<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Fixtures;

use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;

/**
 * @Sonata\ListAction()
 * @Sonata\RemoveRoute()
 * @Sonata\AddChild(class=AnnotationClass::class)
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AnnotationExceptionClass2
{
}
