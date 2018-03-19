<?php

namespace KunicMarko\SonataAnnotationBundle\Admin;

use KunicMarko\SonataAnnotationBundle\Reader\ActionButtonReader;
use KunicMarko\SonataAnnotationBundle\Reader\DashboardActionReader;
use KunicMarko\SonataAnnotationBundle\Reader\DatagridReader;
use KunicMarko\SonataAnnotationBundle\Reader\ExportReader;
use KunicMarko\SonataAnnotationBundle\Reader\FormReader;
use KunicMarko\SonataAnnotationBundle\Reader\ListReader;
use KunicMarko\SonataAnnotationBundle\Reader\RouteReader;
use KunicMarko\SonataAnnotationBundle\Reader\ShowReader;
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
        $this->get(FormReader::class)
            ->configureFields($this->getReflectionClass(), $formMapper);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->get(ListReader::class)
            ->configureFields($this->getReflectionClass(), $listMapper);
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $this->get(ShowReader::class)
            ->configureFields($this->getReflectionClass(), $showMapper);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $this->get(DatagridReader::class)
            ->configureFields($this->getReflectionClass(), $datagridMapper);
    }

    protected function configureRoutes(RouteCollection $collection): void
    {
        $this->get(RouteReader::class)
            ->configureRoutes($this->getReflectionClass(), $collection);
    }

    public function configureActionButtons($action, $object = null): array
    {
        return $this->get(ActionButtonReader::class)
            ->getActions(
                $this->getReflectionClass(),
                parent::configureActionButtons($action, $object)
            );
    }

    public function getDashboardActions(): array
    {
        return $this->get(DashboardActionReader::class)
            ->getActions(
                $this->getReflectionClass(),
                parent::getDashboardActions()
            );
    }

    public function getExportFields(): array
    {
        return $this->get(ExportReader::class)
            ->getFields($this->getReflectionClass()) ?: parent::getExportFields();
    }

    public function getExportFormats(): array
    {
        return $this->get(ExportReader::class)
            ->getFormats($this->getReflectionClass()) ?: parent::getExportFormats();
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
