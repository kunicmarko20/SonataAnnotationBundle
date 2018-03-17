<?php

namespace KunicMarko\SonataAnnotationBundle\Reader;

use Doctrine\Common\Annotations\Reader;
use Sonata\AdminBundle\Mapper\BaseMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
abstract class AbstractReader
{
    protected $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function configureFields(\ReflectionClass $entity, BaseMapper $baseMapper): void
    {
        $this->addPropertiesToMapper(
            $this->findProperties($entity),
            $baseMapper
        );
    }

    protected function findProperties(\ReflectionClass $entity): array
    {
        $properties = [];

        foreach ($entity->getProperties() as $property) {
            if ($annotation = $this->annotationReader->getPropertyAnnotation($property, $this->getAnnotation())) {
                $properties[$property->getName()] = $annotation;
            }
        }

        return $properties;
    }

    protected function addPropertiesToMapper(array $properties, BaseMapper $baseMapper): void
    {
        foreach ($properties as $name => $annotation) {
            $baseMapper->add($name, ...$annotation->getSettings());
        }
    }

    abstract protected function getAnnotation(): string;
}
