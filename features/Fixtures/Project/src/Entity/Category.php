<?php

namespace KunicMarko\SonataAnnotationBundle\Features\Fixtures\Project\Entity;

use Doctrine\ORM\Mapping as ORM;
use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;

/**
 * @Sonata\Admin("Category")
 *
 * @Sonata\ListAction("edit")
 * @Sonata\ListAction("delete")
 *
 * @ORM\Entity
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    public $id;

    /**
     * @Sonata\FormField()
     * @Sonata\ListField()
     * @Sonata\ShowField()
     * @Sonata\DatagridField()
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    public function __construct(string $name = null)
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
