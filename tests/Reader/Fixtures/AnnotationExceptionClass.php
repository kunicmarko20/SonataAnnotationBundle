<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures;

use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;

/**
 * @Sonata\ActionButton()
 *
 * @Sonata\AddRoute()
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class AnnotationExceptionClass
{
    /**
     * @Sonata\ExportAssociationField()
     * @Sonata\ShowAssociationField()
     * @Sonata\ListAssociationField()
     * @Sonata\DatagridAssociationField()
     */
    private $field;
}
