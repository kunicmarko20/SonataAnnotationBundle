<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Grouping;

use Sonata\AdminBundle\Form\FormMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class TabCollection
{
    use SortableElementsTrait;

    /**
     * @var Tab[]
     */
    private $tabs = [];

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function add(Tab $tab): void
    {
        if ($this->has($tab->getName())) {
            throw new \InvalidArgumentException(sprintf(
                'Tab "%s" already in collection.',
                $tab->getName()
            ));
        }

        $this->tabs[$tab->getName()] = $tab;
    }

    public function has(string $name): bool
    {
        return isset($this->tabs[$name]);
    }

    public function get(string $name): Tab
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException(sprintf(
                'Tab "%s" not found in collection.',
                $name
            ));
        }

        return $this->tabs[$name];
    }

    public function isEmpty(): bool
    {
        return \count($this->tabs) === 0;
    }

    public function render(FormMapper $formMapper): void
    {
        $this->tabs = $this->sort(...\array_values($this->tabs));

        foreach ($this->tabs as $tab) {
            $formMapper->tab($tab->getName());

            $tab->getGroupCollection()->render($formMapper);

            $formMapper->end();
        }
    }
}