<?php

declare(strict_types=1);


namespace KunicMarko\SonataAnnotationBundle\Grouping;


use Sonata\AdminBundle\Form\FormMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class FieldCollection
{
    use SortableElementsTrait;

    /**
     * @var Field[]
     */
    private $fields = [];

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function add(Field $field): void
    {
        if (isset($this->fields[$field->getName()])) {
            throw new \InvalidArgumentException(sprintf(
                'Field "%s" already in collection.',
                $field->getName()
            ));
        }

        $this->fields[$field->getName()] = $field;
    }

    public function isEmpty(): bool
    {
        return \count($this->fields) === 0;
    }

    public function render(FormMapper $formMapper): void
    {
        $this->fields = $this->sort(...\array_values($this->fields));

        foreach ($this->fields as $field) {
            $formMapper->add($field->getName(), ...$field->getSettings());
        }
    }
}