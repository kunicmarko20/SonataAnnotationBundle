<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ShowAssociationField extends ShowField
{
    /**
     * @var string
     */
    public $field;

    public function getField(): string
    {
        if ($this->field) {
            return $this->field;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Argument "field" is mandatory in "%s" annotation.',
                self::class
            )
        );
    }
}
