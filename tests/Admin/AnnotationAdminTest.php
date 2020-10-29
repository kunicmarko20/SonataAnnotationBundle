<?php

namespace KunicMarko\SonataAnnotationBundle\Tests\Admin;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Admin\AnnotationAdmin;
use KunicMarko\SonataAnnotationBundle\Reader\ActionButtonReader;
use KunicMarko\SonataAnnotationBundle\Reader\ExportReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationClass;
use PHPUnit\Framework\TestCase;
use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\DependencyInjection\Container;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AnnotationAdminTest extends TestCase
{
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
        $pool->getContainer()->willReturn($this->container);

        $this->admin = new AnnotationAdmin(null, AnnotationClass::class, null);

        $this->admin->setConfigurationPool($pool->reveal());
    }

    public function testConfigureActionButtons(): void
    {
        $this->container->set(
            'sonata.annotation.reader.action_button',
            new ActionButtonReader(new AnnotationReader())
        );

        $actions = $this->admin->configureActionButtons('');

        $this->assertContains('fake_template.html.twig', reset($actions));
    }

    public function testGetExportFormats(): void
    {
        $this->container->set(
            'sonata.annotation.reader.export',
            new ExportReader(new AnnotationReader())
        );

        $formats = $this->admin->getExportFormats('');

        $this->assertSame(['json', 'xml'], $formats);
    }
}
