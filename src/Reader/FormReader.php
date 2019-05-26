<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Reader;

use Doctrine\Common\Annotations\Reader;
use KunicMarko\SonataAnnotationBundle\Annotation;
use KunicMarko\SonataAnnotationBundle\Grouping\Field;
use KunicMarko\SonataAnnotationBundle\Grouping\Group;
use KunicMarko\SonataAnnotationBundle\Grouping\GroupCollection;
use KunicMarko\SonataAnnotationBundle\Grouping\Tab;
use KunicMarko\SonataAnnotationBundle\Grouping\TabCollection;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class FormReader
{
    use AnnotationReaderTrait;

    private const DEFAULT_GROUP_NAME = '-';

    private const DEFAULT_TAB_NAME = 'default';

    public function configureCreateFields(\ReflectionClass $class, FormMapper $formMapper, ?string $defaultGroup = self::DEFAULT_GROUP_NAME): void
    {
        $this->configureFields(
            $class,
            $formMapper,
            Annotation\FormField::ACTION_EDIT,
            Group::with($defaultGroup)
        );
    }

    public function configureEditFields(\ReflectionClass $class, FormMapper $formMapper, ?string $defaultGroup = self::DEFAULT_GROUP_NAME): void
    {
        $this->configureFields(
            $class,
            $formMapper,
            Annotation\FormField::ACTION_CREATE,
            Group::with($defaultGroup)
        );
    }

    private function configureFields(\ReflectionClass $class, FormMapper $formMapper, string $action, Group $defaultGroup): void
    {
        $tabCollection = TabCollection::create();
        $groupCollection = GroupCollection::create();
        $groupCollection->add($defaultGroup);

        $this->findTabsAndGroups(
            $this->getClassAnnotations($class),
            $tabCollection,
            $groupCollection
        );

        $this->fillTabsAndGroupsWithFields(
            $class->getProperties(),
            $action,
            $defaultGroup->getName(),
            $tabCollection,
            $groupCollection
        );

        if ($tabCollection->isEmpty()) {
            $groupCollection->render($formMapper);
            return;
        }

        $this->renderTabCollection($formMapper, $tabCollection, $groupCollection);
    }

    private function findTabsAndGroups(
        array $annotations,
        TabCollection $tabCollection,
        GroupCollection $groupCollection
    ): void {

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Annotation\Tab) {
                $tabCollection->add(Tab::with(
                    $annotation->getName(),
                    $annotation->options,
                    $annotation->position
                ));
                continue;
            }

            if ($annotation instanceof Annotation\Group) {
                $groupCollection->add(Group::with(
                    $annotation->getName(),
                    $annotation->options,
                    $annotation->tab,
                    $annotation->position
                ));
            }
        }
    }

    private function fillTabsAndGroupsWithFields(
        array $properties,
        string $action,
        string $defaultGroupName,
        TabCollection $tabCollection,
        GroupCollection $groupCollection
    ): void {
        foreach ($properties as $property) {
            foreach ($this->getPropertyAnnotations($property) as $annotation) {
                if (!$annotation instanceof Annotation\FormField || $annotation->action === $action) {
                    continue;
                }

                $field = Field::with(
                    $property->getName(),
                    $annotation->getSettings(),
                    $annotation->position,
                    $annotation->getGroup(),
                    $annotation->getTab()
                );

                if ($annotation->hasTab()) {
                    $tabCollection->get($annotation->getTab())
                        ->addField($field);
                    continue;
                }

                $groupCollection->get($annotation->getGroup() ?? $defaultGroupName)
                    ->addField($field);
            }
        }
    }

    private function renderTabCollection(
        FormMapper $formMapper,
        TabCollection $tabCollection,
        GroupCollection $groupCollection
    ): void {
        $tabCollection->add(Tab::with(self::DEFAULT_TAB_NAME));

        foreach ($groupCollection->all() as $group) {
            if ($group->getTabName() === null) {
                continue;
            }

            if ($tabCollection->has($group->getTabName() ?? self::DEFAULT_TAB_NAME)) {
                throw new \InvalidArgumentException(sprintf(
                    'Tab "%s" was not found, but was included in "%s" Group annotation.',
                    $group->getTabName(),
                    $group->getName()
                ));
            }

            $tabCollection->get($group->getTabName())
                ->addGroup($group);
        }

        $tabCollection->render($formMapper);
    }
}
