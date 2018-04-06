<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\ChildAdmin;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ChildAdminReader
{
    use AnnotationReaderTrait;

    public function getChildren(\ReflectionClass $class): array
    {
        $children = [];

        foreach ($this->getClassAnnotations($class) as $annotation) {
            if (!$annotation instanceof ChildAdmin) {
                continue;
            }

            $children[$annotation->getClass()] = $annotation->getField();
        }

        return $children;
    }
}
