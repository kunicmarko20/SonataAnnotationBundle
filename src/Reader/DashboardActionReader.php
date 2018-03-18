<?php

namespace KunicMarko\SonataAnnotationBundle\Reader;

use KunicMarko\SonataAnnotationBundle\Annotation\DashboardAction;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class DashboardActionReader extends AbstractActionReader
{
    protected function isSupported($annotation): bool
    {
        return $annotation instanceof DashboardAction;
    }
}
