<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\DatagridField;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class DatagridReader
{
    use AnnotationReaderTrait;

    public function configureFields(\ReflectionClass $class, DatagridMapper $datagridMapper): void
    {
        foreach ($class->getProperties() as $property) {
            if ($annotation = $this->getPropertyAnnotation($property, DatagridField::class)) {
                $datagridMapper->add($property->getName(), ...$annotation->getSettings());
            }
        }
    }
}
