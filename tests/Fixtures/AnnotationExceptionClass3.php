<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Fixtures;

use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AnnotationExceptionClass3
{
    /**
     * @Sonata\ShowField(position=1)
     */
    private $field;

    /**
     * @Sonata\ShowAssociationField(field="name", position=1)
     */
    private $field2;
}
