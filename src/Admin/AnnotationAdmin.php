<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Admin;

use KunicMarko\SonataAnnotationBundle\Annotation\AddRoute;
use KunicMarko\SonataAnnotationBundle\Annotation\RemoveRoute;
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
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class AnnotationAdmin extends AbstractAdmin
{
    /**
     * @var FormReader
     */
    private $formReader;

    /**
     * @var ListReader
     */
    private $listReader;

    /**
     * @var ShowReader
     */
    private $showReader;

    /**
     * @var DatagridReader
     */
    private $datagridReader;

    /**
     * @var RouteReader
     */
    private $routeReader;

    /**
     * @var ActionButtonReader
     */
    private $actionButtonReader;

    /**
     * @var DashboardActionReader
     */
    private $dashboardActionReader;

    /**
     * @var ExportReader
     */
    private $exportReader;

    public function __construct(
        FormReader $formReader,
        ListReader $listReader,
        ShowReader $showReader,
        DatagridReader $datagridReader,
        RouteReader $routeReader,
        ActionButtonReader $actionButtonReader,
        DashboardActionReader $dashboardActionReader,
        ExportReader $exportReader,
        string $code,
        string $class,
        ?string $baseControllerName = null
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->formReader = $formReader;
        $this->listReader = $listReader;
        $this->showReader = $showReader;
        $this->datagridReader = $datagridReader;
        $this->routeReader = $routeReader;
        $this->actionButtonReader = $actionButtonReader;
        $this->dashboardActionReader = $dashboardActionReader;
        $this->exportReader = $exportReader;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        if ($this->request->get($this->getIdParameter())) {
            $this->formReader->configureEditFields($this->getReflectionClass(), $formMapper);
            return;
        }

        $this->formReader->configureCreateFields($this->getReflectionClass(), $formMapper);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->listReader->configureFields($this->getReflectionClass(), $listMapper);
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $this->showReader->configureFields($this->getReflectionClass(), $showMapper);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $this->datagridReader->configureFields($this->getReflectionClass(), $datagridMapper);
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        [$addRoutes, $removeRoutes] = $this->routeReader->getRoutes($this->getReflectionClass());

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

    public function configureActionButtons(array $buttonList, $action, ?object $object = null): array
    {
        return $this->actionButtonReader->getActions(
            $this->getReflectionClass(),
            parent::configureActionButtons($buttonList, $action, $object)
        );
    }

    public function getDashboardActions(): array
    {
        return $this->dashboardActionReader->getActions(
            $this->getReflectionClass(),
            parent::getDashboardActions()
        );
    }

    public function getExportFormats(): array
    {
        return $this->exportReader->getFormats($this->getReflectionClass()) ?: parent::getExportFormats();
    }

    private function getReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass($this->getClass());
    }
}
