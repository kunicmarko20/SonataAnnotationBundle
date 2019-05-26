<?php


namespace KunicMarko\SonataAnnotationBundle\Grouping;


/**
 * @internal
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
trait SortableElementsTrait
{
    private function sort(SortableElement ...$elements): array
    {
        $elementsWithPosition = [];
        $elementsWithoutPosition = [];

        foreach ($elements as $element) {
            if ($element->getPosition() === null) {
                $elementsWithoutPosition[] = $element;
                continue;
            }

            if (isset($elementsWithPosition[$element->getPosition()])) {
                throw new \InvalidArgumentException(sprintf(
                    'Element "%s" has same position as Element "%s".',
                    $element->getName(),
                    $elementsWithPosition[$element->getPosition()]->getName()
                ));
            }

            $elementsWithPosition[$element->getPosition()] = $element;
        }

        return \array_merge($elementsWithPosition, $elementsWithoutPosition);
    }
}