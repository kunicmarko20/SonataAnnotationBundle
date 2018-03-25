<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\AddRoute;
use KunicMarko\SonataAnnotationBundle\Annotation\RemoveRoute;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class RouteReader
{
    use AnnotationReaderTrait;

    public function getRoutes(\ReflectionClass $class): array
    {
        $addRoutes = [];
        $removeRoutes = [];

        foreach ($this->getClassAnnotations($class) as $annotation) {
            if ($annotation instanceof AddRoute) {
                $addRoutes[$annotation->getName()] = $annotation;
                continue;
            }

            if ($annotation instanceof RemoveRoute) {
                $removeRoutes[$annotation->getName()] = $annotation;
            }
        }

        return [$addRoutes, $removeRoutes];
    }
}
