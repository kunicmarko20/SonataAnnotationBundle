<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use Doctrine\Common\Annotations\Reader;
use KunicMarko\SonataAnnotationBundle\Annotation\AnnotationInterface;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
trait AnnotationReaderTrait
{
    private $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    protected function getClassAnnotation(\ReflectionClass $class, string $annotation): ?AnnotationInterface
    {
        return $this->annotationReader->getClassAnnotation($class, $annotation);
    }

    protected function getClassAnnotations(\ReflectionClass $class): array
    {
        return $this->annotationReader->getClassAnnotations($class);
    }

    protected function getPropertyAnnotation(\ReflectionProperty $property, string $annotation): ?AnnotationInterface
    {
        return $this->annotationReader->getPropertyAnnotation($property, $annotation);
    }

    protected function getMethodAnnotation(\ReflectionMethod $method, string $annotation): ?AnnotationInterface
    {
        return $this->annotationReader->getMethodAnnotation($method, $annotation);
    }
}
