<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\DashboardActionReader;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class DashboardActionReaderTest extends TestCase
{
    /**
     * @var DashboardActionReader
     */
    private $dashboardActionReader;

    protected function setUp(): void
    {
        $this->dashboardActionReader = new DashboardActionReader(new AnnotationReader());
    }

    public function testGetActionsPresentAnnotation(): void
    {
        $actions = $this->dashboardActionReader->getActions(new \ReflectionClass(AnnotationClass::class), []);

        $this->assertContains('fake_template.html.twig', reset($actions));
    }

    public function testGetActionsNoAnnotation(): void
    {
        $actions = $this->dashboardActionReader->getActions(new \ReflectionClass(EmptyClass::class), []);

        $this->assertEmpty($actions);
    }
}
