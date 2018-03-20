<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\ActionButton;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ActionButtonReader extends AbstractActionReader
{
    protected function isSupported($annotation): bool
    {
        return $annotation instanceof ActionButton;
    }
}
