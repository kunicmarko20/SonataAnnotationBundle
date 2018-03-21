<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\ListAction;
use KunicMarko\SonataAnnotationBundle\Annotation\ListField;
use Sonata\AdminBundle\Mapper\BaseMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ListReader extends AbstractReader
{
    public function configureFields(\ReflectionClass $class, BaseMapper $baseMapper): void
    {
        parent::configureFields($class, $baseMapper);

        if ($actions = $this->getListActions($this->getClassAnnotations($class))) {
            $baseMapper->add('_action', null, [
                'actions' => $actions
            ]);
        }
    }

    private function getListActions(array $annotations): array
    {
        $actions = [];

        foreach ($annotations as $annotation) {
            if (!$this->isSupported($annotation)) {
                continue;
            }

            $actions[$annotation->name] = $annotation->options;
        }

        return $actions;
    }

    private function isSupported($annotation): bool
    {
        return $annotation instanceof ListAction;
    }

    protected function findProperties(\ReflectionClass $class): array
    {
        $fields = parent::findProperties($class);

        foreach ($class->getMethods() as $method) {
            if ($annotation = $this->getMethodAnnotation($method, $this->getAnnotation())) {
                $fields[$method->getName()] = $annotation;
            }
        }

        return $fields;
    }

    protected function addPropertiesToMapper(array $properties, BaseMapper $baseMapper): void
    {
        foreach ($properties as $name => $annotation) {
            $this->addField($name, $annotation, $baseMapper);
        }
    }

    private function addField(string $name, ListField $annotation, BaseMapper $baseMapper): void
    {
        if ($annotation->identifier) {
            $baseMapper->addIdentifier($name, ...$annotation->getSettings());
            return;
        }

        $baseMapper->add($name, ...$annotation->getSettings());
    }

    protected function getAnnotation(): string
    {
        return ListField::class;
    }
}
