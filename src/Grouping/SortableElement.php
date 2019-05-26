<?php

namespace KunicMarko\SonataAnnotationBundle\Grouping;


/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
interface SortableElement extends Element
{
    public function getPosition(): ?int;
}
