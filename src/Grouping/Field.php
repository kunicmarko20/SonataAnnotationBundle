<?php

declare(strict_types=1);


namespace KunicMarko\SonataAnnotationBundle\Grouping;

use KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class Field implements SortableElement
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $settings;

    /**
     * @var integer|null
     */
    private $position;

    /**
     * @var string|null
     */
    private $groupName;

    /**
     * @var string|null
     */
    private $tabName;

    /**
     * @var Tab|null
     */
    private $tab;

    /**
     * @var Group|null
     */
    private $group;

    private function __construct(string $name, array $settings, ?int $position = null, ?string $groupName = null, ?string $tabName = null)
    {
        $this->name = $name;
        $this->settings = $settings;
        $this->position = $position;
        $this->groupName = $groupName;
        $this->tabName = $tabName;
    }

    public static function with(string $name, array $settings, ?int $position = null, ?string $groupName = null, ?string $tabName = null): self
    {
        return new self($name, $settings, $position, $groupName, $tabName);
    }

    public function setTab(Tab $tab): void
    {
        if ($this->tabName !== null && $tab->getName() !== $this->tabName) {
            throw new \InvalidArgumentException(sprintf(
                'Field "%s" has "%s" as a Tab, trying to add it to "%s" Tab failed.',
                $this->name,
                $this->tabName,
                $tab->getName()
            ));
        }

        if ($this->tab !== null) {
            throw new \InvalidArgumentException(sprintf(
                'Field "%s" already has "%s" as a Tab, unable to add field to "%s" Tab.',
                $this->name,
                $this->tab->getName(),
                $tab->getName()
            ));
        }

        if ($this->group !== null) {
            throw new \InvalidArgumentException(sprintf(
                'Field "%s" already has "%s" as a Group, unable to add field to "%s" Tab.',
                $this->name,
                $this->group->getName(),
                $tab->getName()
            ));
        }

        $this->tab = $tab;
    }

    public function setGroup(Group $group): void
    {
        if ($this->groupName !== null && $group->getName() !== $this->groupName) {
            throw new \InvalidArgumentException(sprintf(
                'Field "%s" has "%s" as a Group, trying to add it to "%s" Group failed.',
                $this->name,
                $this->groupName,
                $group->getName()
            ));
        }

        if ($this->group !== null) {
            throw new \InvalidArgumentException(sprintf(
                'Field "%s" already has "%s" as a Group, unable to add field to "%s" Group.',
                $this->name,
                $this->group->getName(),
                $group->getName()
            ));
        }

        if ($this->tab !== null) {
            throw new \InvalidArgumentException(sprintf(
                'Field "%s" already has "%s" as a Tab, unable to add field to "%s" Group.',
                $this->name,
                $this->tab->getName(),
                $group->getName()
            ));
        }

        $this->group = $group;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }
}