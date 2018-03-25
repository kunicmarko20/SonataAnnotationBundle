<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Fixtures;

use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;

/**
 * @Sonata\Admin(
 *     serviceId="test.the.id"
 * )
 *
 * @Sonata\Access("ROLE_VENDOR", permissions={"ALL"})
 * @Sonata\ActionButton()
 *
 * @Sonata\AddRoute()
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AnnotationExceptionClass
{
    /**
     * @Sonata\ExportAssociationField()
     * @Sonata\ShowAssociationField()
     * @Sonata\ListAssociationField()
     * @Sonata\DatagridAssociationField()
     */
    private $field;
}
