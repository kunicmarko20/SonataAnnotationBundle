<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\ListAction;
use KunicMarko\SonataAnnotationBundle\Annotation\ListField;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ListReader
{
    use AnnotationReaderTrait;

    public function configureFields(\ReflectionClass $class, ListMapper $listMapper): void
    {
        foreach ($class->getProperties() as $property) {
            if ($annotation = $this->getPropertyAnnotation($property, ListField::class)) {
                $this->addField($property->getName(), $annotation, $listMapper);
            }
        }

        foreach ($class->getMethods() as $method) {
            if ($annotation = $this->getMethodAnnotation($method, ListField::class)) {
                $this->addField($property->getName(), $annotation, $listMapper);
            }
        }

        if ($actions = $this->getListActions($this->getClassAnnotations($class))) {
            $listMapper->add('_action', null, [
                'actions' => $actions
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
                $actions[$annotation->name] = $annotation->options;
            }
        }

        return $actions;
    }
}
