<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Admin;

use KunicMarko\SonataAnnotationBundle\Annotation\AddRoute;
use KunicMarko\SonataAnnotationBundle\Reader\ActionButtonReader;
use KunicMarko\SonataAnnotationBundle\Reader\DashboardActionReader;
use KunicMarko\SonataAnnotationBundle\Reader\DatagridReader;
use KunicMarko\SonataAnnotationBundle\Reader\DatagridValuesReader;
use KunicMarko\SonataAnnotationBundle\Reader\ExportReader;
use KunicMarko\SonataAnnotationBundle\Reader\FormReader;
use KunicMarko\SonataAnnotationBundle\Reader\ListReader;
use KunicMarko\SonataAnnotationBundle\Reader\ParentAssociationMappingReader;
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
    private $parentAssociationMappingLoaded = false;
    private $datagridValuesLoaded = false;

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
        [$addRoutes, $removeRoutes] = $this->get(RouteReader::class)->getRoutes($this->getReflectionClass());

        foreach ($addRoutes as $route) {
            $collection->add($route->name, $this->replaceIdParameterInRoutePath($route->path));
        }

        foreach ($removeRoutes as $route) {
            $collection->remove($route->name);
        }
    }

    private function replaceIdParameterInRoutePath(string $path): string
    {
        return str_replace(AddRoute::ID_PARAMETER, $this->getRouterIdParameter(), $path);
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

    public function buildDatagrid()
    {
        if (!$this->datagridValuesLoaded) {
            $this->datagridValues = $this->get(DatagridValuesReader::class)
                ->getDatagridValues($this->getReflectionClass()) ?: $this->datagridValues;

            $this->datagridValuesLoaded = true;
        }

        parent::buildDatagrid();
    }

    public function getParentAssociationMapping()
    {
        if (!$this->parentAssociationMappingLoaded) {
            $this->parentAssociationMapping = $this->get(ParentAssociationMappingReader::class)
                ->getParent($this->getReflectionClass()) ?: $this->parentAssociationMapping;

            $this->parentAssociationMappingLoaded = true;
        }

        return $this->parentAssociationMapping;
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
