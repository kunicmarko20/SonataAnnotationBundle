<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Grouping;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class Tab implements SortableElement
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $options;

    /**
     * @var int|null
     */
    private $position;

    /**
     * @var GroupCollection
     */
    private $groupCollection;

    /**
     * @var FieldCollection
     */
    private $fieldsCollection;

    private function __construct(string $name, array $options = [], ?int $position = null)
    {
        $this->name = $name;
        $this->options = $options;
        $this->position = $position;
        $this->groupCollection = GroupCollection::create();
        $this->fieldsCollection = FieldCollection::create();
    }

    public static function with(string $name, array $options = [], ?int $position = null): self
    {
        return new self($name, $options, $position);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addGroup(Group $group): void
    {
        if (!$this->fieldsCollection->isEmpty()) {
            throw new \InvalidArgumentException(sprintf(
                'Tab "%s" already has field(s), it can either have groups with fields or just field.',
                $this->name
            ));
        }

        $group->setTab($this);
        $this->groupCollection->add($group);
    }

    public function addField(Field $field): void
    {
        if (!$this->groupCollection->isEmpty()) {
            throw new \InvalidArgumentException(sprintf(
                'Tab "%s" already has group(s), it can either have groups with fields or just field.',
                $this->name
            ));
        }

        $field->setTab($this);
        $this->fieldsCollection->add($field);
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getGroupCollection(): GroupCollection
    {
        return $this->groupCollection;
    }
}