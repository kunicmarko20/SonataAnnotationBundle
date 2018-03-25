<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\FormField;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class FormReader
{
    use AnnotationReaderTrait;

    public function configureCreateFields(\ReflectionClass $class, FormMapper $formMapper): void
    {
        $this->configureFields($class, $formMapper, FormField::ACTION_EDIT);
    }

    private function configureFields(\ReflectionClass $class, FormMapper $formMapper, string $action): void
    {
        foreach ($class->getProperties() as $property) {
            foreach ($this->getPropertyAnnotations($property) as $annotation) {
                if (!$annotation instanceof FormField || $annotation->action === $action) {
                    continue;
                }

                $formMapper->add($property->getName(), ...$annotation->getSettings());
            }
        }
    }

    public function configureEditFields(\ReflectionClass $class, FormMapper $formMapper): void
    {
        $this->configureFields($class, $formMapper, FormField::ACTION_CREATE);
    }
}
