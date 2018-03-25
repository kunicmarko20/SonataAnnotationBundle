<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class Access implements AnnotationInterface
{
    /**
     * @var string
     */
    public $role;

    /**
     * @var array
     */
    public $permissions;

    public function getRole(): string
    {
        if ($this->role) {
            return $this->role;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Argument "role" is mandatory in "%s" annotation.',
                self::class
            )
        );
    }
}
