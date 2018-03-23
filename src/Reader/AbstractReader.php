<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use Sonata\AdminBundle\Mapper\BaseMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
abstract class AbstractReader
{
    use AnnotationReaderTrait;

    public function configureFields(\ReflectionClass $class, BaseMapper $baseMapper): void
    {
        $this->addPropertiesToMapper(
            $this->findProperties($class),
            $baseMapper
        );
    }

    protected function findProperties(\ReflectionClass $class): array
    {
        $properties = [];

        foreach ($class->getProperties() as $property) {
            if ($annotation = $this->getPropertyAnnotation($property, $this->getAnnotation())) {
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
