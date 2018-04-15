<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\AddChild;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AddChildReader
{
    use AnnotationReaderTrait;

    public function getChildren(\ReflectionClass $class): array
    {
        $children = [];

        foreach ($this->getClassAnnotations($class) as $annotation) {
            if (!$annotation instanceof AddChild) {
                continue;
            }

            $children[$annotation->getClass()] = $annotation->getField();
        }

        return $children;
    }
}
