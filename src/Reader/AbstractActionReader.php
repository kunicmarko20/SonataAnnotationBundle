<?php

namespace KunicMarko\SonataAnnotationBundle\Reader;

use Doctrine\Common\Annotations\Reader;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
abstract class AbstractActionReader
{
    protected $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function configureActions(\ReflectionClass $entity, array $actions): array
    {
        $annotations = $this->annotationReader->getClassAnnotations($entity);

        foreach ($annotations as $annotation) {
            if (!$this->isSupported($annotation)) {
                continue;
            }

            $actions[random_int(-99999, 99999)]['template'] = $annotation->template;
        }

        return $actions;
    }

    abstract protected function isSupported($annotation): bool;
}
