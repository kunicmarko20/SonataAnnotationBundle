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
        //
        // Properties
        //

        $propertiesWithPosition = [];
        $propertiesWithoutPosition = [];

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
                    $propertiesWithoutPosition[] = [
                        'name' => $name,
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
                    'name' => $name,
                    'settings' => $annotation->getSettings(),
                ];
            }
        }

        \ksort($propertiesWithPosition);

        $properties = \array_merge($propertiesWithPosition, $propertiesWithoutPosition);

        foreach ($properties as $property) {
            $showMapper->add($property['name'], ...$property['settings']);
        }

        //
        // Methods
        //

        $methodsWithPosition = [];
        $methodsWithoutPosition = [];

        foreach ($class->getMethods() as $method) {
            if ($annotation = $this->getMethodAnnotation($method, ShowField::class)) {
                if (!$annotation->hasPosition()) {
                    $methodsWithoutPosition[] = [
                        'name' => $method->getName(),
                        'settings' => $annotation->getSettings(),
                    ];

                    continue;
                }

                if (\array_key_exists($annotation->position, $methodsWithPosition)) {
                    throw new \InvalidArgumentException(sprintf(
                        'Position "%s" is already in use by "%s", try setting a different position for "%s".',
                        $annotation->position,
                        $methodsWithPosition[$annotation->position]['name'],
                        $method->getName()
                    ));
                }

                $methodsWithPosition[$annotation->position] = [
                    'name' => $name,
                    'settings' => $annotation->getSettings(),
                ];
            }
        }

        \ksort($methodsWithPosition);

        $methods = \array_merge($methodsWithPosition, $methodsWithoutPosition);

        foreach ($methods as $method) {
            $showMapper->add($method['name'], ...$method['settings']);
        }
    }
}
