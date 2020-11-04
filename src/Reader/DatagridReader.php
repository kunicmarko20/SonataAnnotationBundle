<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\DatagridAssociationField;
use KunicMarko\SonataAnnotationBundle\Annotation\DatagridField;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class DatagridReader
{
    use AnnotationReaderTrait;

    public function configureFields(\ReflectionClass $class, DatagridMapper $datagridMapper): void
    {
        $propertiesWithPosition = [];
        $propertiesWithoutPosition = [];

        foreach ($class->getProperties() as $property) {
            foreach ($this->getPropertyAnnotations($property) as $annotation) {
                if (!$annotation instanceof DatagridField && !$annotation instanceof DatagridAssociationField) {
                    continue;
                }

                // the name property changes for DatagridAssociationField
                $name = $property->getName();
                if ($annotation instanceof DatagridAssociationField) {
                    $name .= '.'.$annotation->getField();
                }

                if (!$annotation->hasPosition()) {
                    $propertiesWithoutPosition[] = [
                        'name' => $name,
                        'annotation' => $annotation,
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
                    'annotation' => $annotation,
                ];
            }
        }

        \ksort($propertiesWithPosition);

        $properties = \array_merge($propertiesWithPosition, $propertiesWithoutPosition);

        foreach ($properties as $property) {
            $datagridMapper->add(
                $property['name'], ...$property['annotation']->getSettings()
            );
        }
    }
}
