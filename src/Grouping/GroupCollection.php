<?php

declare(strict_types=1);


namespace KunicMarko\SonataAnnotationBundle\Grouping;


use Sonata\AdminBundle\Form\FormMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class GroupCollection
{
    use SortableElementsTrait;

    /**
     * @var Group[]
     */
    private $groups = [];

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function add(Group $group): void
    {
        if (isset($this->groups[$group->getName()])) {
            throw new \InvalidArgumentException(sprintf(
                'Group "%s" already in collection.',
                $group->getName()
            ));
        }

        $this->groups[$group->getName()] = $group;
    }

    /**
     * @return Group[]
     */
    public function all(): array
    {
        return $this->groups;
    }

    public function has(string $name): bool
    {
        return isset($this->groups[$name]);
    }

    public function get(string $name): Group
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException(sprintf(
                'Group "%s" not found in collection.',
                $name
            ));
        }

        return $this->groups[$name];
    }

    public function isEmpty(): bool
    {
        return \count($this->groups) === 0;
    }

    public function render(FormMapper $formMapper): void
    {
        $this->groups = $this->sort(...\array_values($this->groups));

        foreach ($this->groups as $group) {
            $formMapper->with($group->getName());

            $group->getFieldCollection()->render($formMapper);

            $formMapper->end();
        }
    }
}