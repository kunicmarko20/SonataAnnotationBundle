<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
abstract class AbstractActionReader
{
    use AnnotationReaderTrait;

    public function getActions(\ReflectionClass $class, array $actions): array
    {
        foreach ($this->getClassAnnotations($class) as $annotation) {
            if (!$this->isSupported($annotation)) {
                continue;
            }

            $actions[random_int(-99999, 99999)]['template'] = $annotation->template;
        }

        return $actions;
    }

    abstract protected function isSupported($annotation): bool;
}
