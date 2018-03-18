<?php

namespace KunicMarko\SonataAnnotationBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class Admin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $this->get('sonata.annotation.reader.form')
            ->configureFields($this->getReflectionClass(), $formMapper);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->get('sonata.annotation.reader.list')
            ->configureFields($this->getReflectionClass(), $listMapper);
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $this->get('sonata.annotation.reader.show')
            ->configureFields($this->getReflectionClass(), $showMapper);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $this->get('sonata.annotation.reader.datagrid')
            ->configureFields($this->getReflectionClass(), $datagridMapper);
    }

    protected function configureRoutes(RouteCollection $collection): void
    {
        $this->get('sonata.annotation.reader.route')
            ->configureRoutes($this->getReflectionClass(), $collection);
    }

    public function configureActionButtons($action, $object = null): array
    {
        return $this->get('sonata.annotation.reader.action_button')
            ->configureActions(
                $this->getReflectionClass(),
                parent::configureActionButtons($action, $object)
            );
    }

    public function getDashboardActions(): array
    {
        return $this->get('sonata.annotation.reader.dashboard_action')
            ->configureActions(
                $this->getReflectionClass(),
                parent::getDashboardActions()
            );
    }

    private function get(string $service)
    {
        return $this->getConfigurationPool()->getContainer()->get($service);
    }

    private function getReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass($this->getClass());
    }
}
