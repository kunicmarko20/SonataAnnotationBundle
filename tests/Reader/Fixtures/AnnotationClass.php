<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures;

use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;

/**
 * @Sonata\ActionButton("fake_template.html.twig")
 * @Sonata\DashboardAction("fake_template.html.twig")
 * @Sonata\ExportFormats({"json", "xml"})
 *
 *
 *
 *
 *
 *
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class AnnotationClass
{
    /**
     * @Sonata\ParentAssociationMapping()
     * @Sonata\ExportField()
     */
    private $field;

    /**
     * @Sonata\ExportField("label")
     */
    private $additionalField;

    /**
     * @Sonata\ExportField()
     */
    public function method(): string
    {
        return 'value';
    }
}
