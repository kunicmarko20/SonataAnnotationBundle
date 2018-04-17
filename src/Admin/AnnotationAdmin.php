<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Admin;

use KunicMarko\SonataAnnotationBundle\Annotation\AddRoute;
use KunicMarko\SonataAnnotationBundle\Annotation\RemoveRoute;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class AnnotationAdmin extends AbstractAdmin
{
    private $datagridValuesLoaded = false;

    protected function configureFormFields(FormMapper $formMapper): void
    {
        if ($this->request->get($this->getIdParameter())) {
            $this->get('sonata.annotation.reader.form')
                ->configureEditFields($this->getReflectionClass(), $formMapper);
            return;
        }

        $this->get('sonata.annotation.reader.form')
            ->configureCreateFields($this->getReflectionClass(), $formMapper);
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
        [$addRoutes, $removeRoutes] = $this->get('sonata.annotation.reader.route')
            ->getRoutes($this->getReflectionClass());

        /** @var AddRoute $route */
        foreach ($addRoutes as $route) {
            $collection->add(
                $route->getName(),
                $route->path ? $this->replaceIdParameterInRoutePath($route->path) : $route->getName()
            );
        }

        /** @var RemoveRoute $route */
        foreach ($removeRoutes as $route) {
            $collection->remove($route->getName());
        }
    }

    private function replaceIdParameterInRoutePath(string $path): string
    {
        return str_replace(AddRoute::ID_PARAMETER, $this->getRouterIdParameter(), $path);
    }

    public function configureActionButtons($action, $object = null): array
    {
        return $this->get('sonata.annotation.reader.action_button')
            ->getActions(
                $this->getReflectionClass(),
                parent::configureActionButtons($action, $object)
            );
    }

    public function getDashboardActions(): array
    {
        return $this->get('sonata.annotation.reader.dashboard_action')
            ->getActions(
                $this->getReflectionClass(),
                parent::getDashboardActions()
            );
    }

    public function getExportFields(): array
    {
        return $this->get('sonata.annotation.reader.export')
            ->getFields($this->getReflectionClass()) ?: parent::getExportFields();
    }

    public function getExportFormats(): array
    {
        return $this->get('sonata.annotation.reader.export')
            ->getFormats($this->getReflectionClass()) ?: parent::getExportFormats();
    }

    public function buildDatagrid(): void
    {
        if (!$this->datagridValuesLoaded) {
            $this->datagridValues = $this->get('sonata.annotation.reader.datagrid_values')
                ->getDatagridValues($this->getReflectionClass()) ?: $this->datagridValues;

            $this->datagridValuesLoaded = true;
        }

        parent::buildDatagrid();
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
