<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use Doctrine\Common\Annotations\Reader;
use KunicMarko\SonataAnnotationBundle\Annotation\Route;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class RouteReader
{
    use AnnotationReaderTrait;

    public function getRoutes(\ReflectionClass $class): array
    {
        $routes = [];

        foreach ($this->getClassAnnotations($class) as $annotation) {
            if (!$this->isSupported($annotation)) {
                continue;
            }

            $routes[$annotation->name] = $annotation;
        }

        return $routes;
    }

    private function isSupported($annotation): bool
    {
        return $annotation instanceof Route;
    }
}
