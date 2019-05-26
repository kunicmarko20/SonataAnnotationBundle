<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Grouping;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class Group implements SortableElement
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var Tab|null
     */
    private $tab;

    /**
     * @var string|null
     */
    private $tabName;

    /**
     * @var int|null
     */
    private $position;

    /**
     * @var FieldCollection
     */
    private $fieldCollection;

    private function __construct(string $name, array $options = [], ?string $tabName = null, ?int $position = null)
    {
        $this->name = $name;
        $this->options = $options;
        $this->tabName = $tabName;
        $this->position = $position;
        $this->fieldCollection = FieldCollection::create();
    }

    public static function with(string $name, array $options = [], ?string $tabName = null, ?int $position = null): self
    {
        return new self($name, $options, $tabName, $position);
    }

    public function setTab(Tab $tab): void
    {
        if ($tab->getName() !== $this->tabName) {
            throw new \InvalidArgumentException(sprintf(
                'Group "%s" has "%s" as a Tab, trying to add it to "%s" Tab failed.',
                $this->name,
                $this->tabName,
                $tab->getName()
            ));
        }

        if ($this->tab !== null) {
            throw new \InvalidArgumentException(sprintf(
                'Group "%s" already has "%s" as a Tab, unable to add group to "%s" Tab.',
                $this->name,
                $this->tab->getName(),
                $tab->getName()
            ));
        }

        $this->tab = $tab;
    }

    public function getTabName(): ?string
    {
        return $this->tabName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addField(Field $field): void
    {
        $field->setGroup($this);
        $this->fieldCollection->add($field);
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getFieldCollection(): FieldCollection
    {
        return $this->fieldCollection;
    }
}