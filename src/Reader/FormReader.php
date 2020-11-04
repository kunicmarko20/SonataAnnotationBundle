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
        $propertiesWithPosition = [];
        $propertiesWithoutPosition = [];

        foreach ($class->getProperties() as $property) {
            foreach ($this->getPropertyAnnotations($property) as $annotation) {
                if (!$annotation instanceof FormField || $annotation->action === $action) {
                    continue;
                }

                if (!$annotation->hasPosition()) {
                    $propertiesWithoutPosition[] = [
                        'name' => $property->getName(),
                        'settings' => $annotation->getSettings(),
                    ];

                    continue;
                }

                if (\array_key_exists($annotation->position, $propertiesWithPosition)) {
                    throw new \InvalidArgumentException(sprintf(
                        'Position "%s" is already in use by "%s", try setting a different position for "%s".',
                        $annotation->position,
                        $propertiesWithPosition[$annotation->position]['name'],
                        $property->getName()
                    ));
                }

                $propertiesWithPosition[$annotation->position] = [
                    'name' => $property->getName(),
                    'settings' => $annotation->getSettings(),
                ];
            }
        }

        \ksort($propertiesWithPosition);

        $properties = \array_merge($propertiesWithPosition, $propertiesWithoutPosition);

        foreach ($properties as $property) {
            $formMapper->add($property['name'], ...$property['settings']);
        }
    }

    public function configureEditFields(\ReflectionClass $class, FormMapper $formMapper): void
    {
        $this->configureFields($class, $formMapper, FormField::ACTION_CREATE);
    }
}
