<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\ShowAssociationField;
use KunicMarko\SonataAnnotationBundle\Annotation\ShowField;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ShowReader
{
    use AnnotationReaderTrait;

    public function configureFields(\ReflectionClass $class, ShowMapper $showMapper): void
    {
        $propertiesAndMethodsWithPosition = [];
        $propertiesAndMethodsWithoutPosition = [];

        //
        // Properties
        //

        foreach ($class->getProperties() as $property) {
            foreach ($this->getPropertyAnnotations($property) as $annotation) {
                if (!$annotation instanceof ShowField && !$annotation instanceof ShowAssociationField) {
                    continue;
                }

                // the name property changes for ShowAssociationField
                $name = $property->getName();
                if ($annotation instanceof ShowAssociationField) {
                    $name .= '.'.$annotation->getField();
                }

                if (!$annotation->hasPosition()) {
                    $propertiesAndMethodsWithoutPosition[] = [
                        'name' => $name,
                        'settings' => $annotation->getSettings(),
                    ];

                    continue;
                }

                if (\array_key_exists($annotation->position, $propertiesAndMethodsWithPosition)) {
                    throw new \InvalidArgumentException(sprintf(
                        'Position "%s" is already in use by "%s", try setting a different position for "%s".',
                        $annotation->position,
                        $propertiesAndMethodsWithPosition[$annotation->position]['name'],
                        $property->getName()
                    ));
                }

                $propertiesAndMethodsWithPosition[$annotation->position] = [
                    'name' => $name,
                    'settings' => $annotation->getSettings(),
                ];
            }
        }

        //
        // Methods
        //

        foreach ($class->getMethods() as $method) {
            if ($annotation = $this->getMethodAnnotation($method, ShowField::class)) {
                $name = $method->getName();

                if (!$annotation->hasPosition()) {
                    $propertiesAndMethodsWithoutPosition[] = [
                        'name' => $name,
                        'settings' => $annotation->getSettings(),
                    ];

                    continue;
                }

                if (\array_key_exists($annotation->position, $propertiesAndMethodsWithPosition)) {
                    throw new \InvalidArgumentException(sprintf(
                        'Position "%s" is already in use by "%s", try setting a different position for "%s".',
                        $annotation->position,
                        $propertiesAndMethodsWithPosition[$annotation->position]['name'],
                        $name
                    ));
                }

                $propertiesAndMethodsWithPosition[$annotation->position] = [
                    'name' => $name,
                    'settings' => $annotation->getSettings(),
                ];
            }
        }

        //
        // Sorting
        //

        \ksort($propertiesAndMethodsWithPosition);

        $propertiesAndMethods = \array_merge($propertiesAndMethodsWithPosition, $propertiesAndMethodsWithoutPosition);

        foreach ($propertiesAndMethods as $propertyAndMethod) {
            $showMapper->add($propertyAndMethod['name'], ...$propertyAndMethod['settings']);
        }
    }
}
