<?php

namespace KunicMarko\SonataAnnotationBundle\Reader;

use Doctrine\Common\Annotations\Reader;
use KunicMarko\SonataAnnotationBundle\Annotation\Route;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class RouteReader
{
    protected $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function configureRoutes(\ReflectionClass $entity, RouteCollection $collection): void
    {
        $annotations = $this->annotationReader->getClassAnnotations($entity);

        foreach ($annotations as $annotation) {
            if (!$this->isSupported($annotation)) {
                continue;
            }

            $collection->{$annotation->option}(...$annotation->getArgumentsByOption());
        }
    }

    private function isSupported($annotation): bool
    {
        return $annotation instanceof Route;
    }
}
