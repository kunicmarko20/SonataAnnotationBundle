<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\ShowField;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ShowReader
{
    use AnnotationReaderTrait;

    public function configureFields(\ReflectionClass $class, ShowMapper $showMapper): void
    {
        foreach ($class->getProperties() as $property) {
            if ($annotation = $this->getPropertyAnnotation($property, ShowField::class)) {
                $showMapper->add($property->getName(), ...$annotation->getSettings());
            }
        }

        foreach ($class->getMethods() as $method) {
            if ($annotation = $this->getMethodAnnotation($method, ShowField::class)) {
                $showMapper->add($method->getName(), ...$annotation->getSettings());
            }
        }
    }
}
