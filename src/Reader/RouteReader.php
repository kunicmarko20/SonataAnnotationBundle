<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\AddRoute;
use KunicMarko\SonataAnnotationBundle\Annotation\RemoveRoute;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class RouteReader
{
    use AnnotationReaderTrait;

    public function getRoutes(\ReflectionClass $class): array
    {
        $addRoutes = [];
        $removeRoutes = [];

        foreach ($this->getClassAnnotations($class) as $annotation) {
            if ($this->isAddRoute($annotation)) {
                $addRoutes[$annotation->name] = $annotation;
                continue;
            }

            if ($this->isRemoveRoute($annotation)) {
                $removeRoutes[$annotation->name] = $annotation;
            }
        }

        return [$addRoutes, $removeRoutes];
    }

    private function isAddRoute($annotation): bool
    {
        return $annotation instanceof AddRoute;
    }

    private function isRemoveRoute($annotation): bool
    {
        return $annotation instanceof RemoveRoute;
    }
}
