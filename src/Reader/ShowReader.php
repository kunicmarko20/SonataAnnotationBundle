<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\ShowField;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ShowReader extends AbstractReader
{
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

    protected function getAnnotation(): string
    {
        return ShowField::class;
    }
}
