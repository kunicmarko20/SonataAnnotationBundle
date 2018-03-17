<?php

namespace KunicMarko\SonataAnnotationBundle\Reader;

use Doctrine\Common\Annotations\Reader;
use KunicMarko\SonataAnnotationBundle\Annotation\Admin;
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
        $this->addRoutesToCollection(
            $this->getAnnotation($entity),
            $collection
        );
    }

    private function getAnnotation(\ReflectionClass $entity): Admin
    {
        return $this->annotationReader->getClassAnnotation(
            $entity,
            Admin::class
        );
    }

    private function addRoutesToCollection(Admin $annotation, RouteCollection $collection): void
    {
        foreach ($annotation->routes as $name => $path) {
            $collection->add($name, $path);
        }
    }
}
