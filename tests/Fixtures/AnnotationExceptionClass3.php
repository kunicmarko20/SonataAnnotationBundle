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
final class AnnotationExceptionClass3
{
    /**
     * @Sonata\ParentAssociationMapping()
     * @Sonata\ExportAssociationField()
     * @Sonata\ShowField(position=1)
     * @Sonata\ListAssociationField()
     * @Sonata\DatagridAssociationField()
     * @Sonata\FormField()
     */
    private $field;

    /**
     * @Sonata\FormField()
     * @Sonata\ShowField(position=1)
     */
    private $field2;
}
