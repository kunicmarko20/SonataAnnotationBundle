<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\ListAction;
use KunicMarko\SonataAnnotationBundle\Annotation\ListAssociationField;
use KunicMarko\SonataAnnotationBundle\Annotation\ListField;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ListReader
{
    use AnnotationReaderTrait;

    public function configureFields(\ReflectionClass $class, ListMapper $listMapper): void
    {
        $propertiesAndMethodsWithPosition = [];
        $propertiesAndMethodsWithoutPosition = [];

        //
        // Properties
        //

        foreach ($class->getProperties() as $property) {
            foreach ($this->getPropertyAnnotations($property) as $annotation) {
                if (!$annotation instanceof ListField && !$annotation instanceof ListAssociationField) {
                    continue;
                }

                // the name property changes for ListAssociationField
                $name = $property->getName();
                if ($annotation instanceof ListAssociationField) {
                    $name .= '.'.$annotation->getField();
                }

                if (!$annotation->hasPosition()) {
                    $propertiesAndMethodsWithoutPosition[] = [
                        'name' => $name,
                        'annotation' => $annotation,
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
                    'annotation' => $annotation,
                ];
            }
        }

        //
        // Methods
        //

        foreach ($class->getMethods() as $method) {
            if ($annotation = $this->getMethodAnnotation($method, ListField::class)) {
                $name = $method->getName();

                if (!$annotation->hasPosition()) {
                    $propertiesAndMethodsWithoutPosition[] = [
                        'name' => $name,
                        'annotation' => $annotation,
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
                    'annotation' => $annotation,
                ];
            }
        }

        //
        // Sorting
        //

        \ksort($propertiesAndMethodsWithPosition);

        $propertiesAndMethods = \array_merge($propertiesAndMethodsWithPosition, $propertiesAndMethodsWithoutPosition);

        foreach ($propertiesAndMethods as $propertyAndMethod) {
            $this->addField($propertyAndMethod['name'], $propertyAndMethod['annotation'], $listMapper);
        }

        //
        // Actions
        //

        if ($actions = $this->getListActions($this->getClassAnnotations($class))) {
            $listMapper->add('_action', null, [
                'actions' => $actions,
            ]);
        }
    }

    private function addField(string $name, ListField $annotation, ListMapper $listMapper): void
    {
        if ($annotation->identifier) {
            $listMapper->addIdentifier($name, ...$annotation->getSettings());

            return;
        }

        $listMapper->add($name, ...$annotation->getSettings());
    }

    private function getListActions(array $annotations): array
    {
        $actions = [];

        foreach ($annotations as $annotation) {
            if ($annotation instanceof ListAction) {
                $actions[$annotation->getName()] = $annotation->options;
            }
        }

        return $actions;
    }
}
