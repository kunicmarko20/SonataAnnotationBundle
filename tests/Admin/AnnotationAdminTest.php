<?php

namespace KunicMarko\SonataAnnotationBundle\Tests\Admin;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Admin\AnnotationAdmin;
use KunicMarko\SonataAnnotationBundle\Reader\ActionButtonReader;
use KunicMarko\SonataAnnotationBundle\Reader\DashboardActionReader;
use KunicMarko\SonataAnnotationBundle\Reader\DatagridReader;
use KunicMarko\SonataAnnotationBundle\Reader\ExportReader;
use KunicMarko\SonataAnnotationBundle\Reader\FormReader;
use KunicMarko\SonataAnnotationBundle\Reader\ListReader;
use KunicMarko\SonataAnnotationBundle\Reader\RouteReader;
use KunicMarko\SonataAnnotationBundle\Reader\ShowReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\DependencyInjection\Container;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AnnotationAdminTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var AnnotationAdmin
     */
    private $admin;

    /**
     * @var Container
     */
    private $container;

    protected function setUp(): void
    {
        $this->container = new Container();

        $pool = $this->prophesize(Pool::class);

        $mock = new AnnotationReader();

        $this->admin = new AnnotationAdmin(
            '',
            AnnotationClass::class,
            null,
            new FormReader($mock),
            new ListReader($mock),
            new ShowReader($mock),
            new DatagridReader($mock),
            new RouteReader($mock),
            new ActionButtonReader($mock),
            new DashboardActionReader($mock),
            new ExportReader($mock)
        );

        $this->admin->setConfigurationPool($pool->reveal());
    }

    public function testConfigureActionButtons(): void
    {
        $this->container->set(
            'sonata.annotation.reader.action_button',
            new ActionButtonReader(new AnnotationReader())
        );

        $actions = $this->admin->configureActionButtons([], '');

        $this->assertContains('fake_template.html.twig', reset($actions));
    }

    public function testGetExportFormats(): void
    {
        $this->container->set(
            'sonata.annotation.reader.export',
            new ExportReader(new AnnotationReader())
        );

        $formats = $this->admin->getExportFormats();

        $this->assertSame(['json', 'xml'], $formats);
    }
}
