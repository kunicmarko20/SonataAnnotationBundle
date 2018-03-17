<?php

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\Admin;
use KunicMarko\SonataAnnotationBundle\Annotation\ListField;
use Sonata\AdminBundle\Mapper\BaseMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ListReader extends AbstractReader
{
    public function configureFields(\ReflectionClass $entity, BaseMapper $baseMapper): void
    {
        parent::configureFields($entity, $baseMapper);

        $this->addListActions($entity, $baseMapper);
    }

    private function addListActions(\ReflectionClass $entity, BaseMapper $baseMapper): void
    {
        $annotation = $this->annotationReader->getClassAnnotation($entity, Admin::class);

        $baseMapper->add('_action', null, [
            'actions' => $annotation->actions
        ]);
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
