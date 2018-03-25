<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\ActionButtonReader;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\AnnotationClass;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\AnnotationExceptionClass;
use KunicMarko\SonataAnnotationBundle\Tests\Reader\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ActionButtonReaderTest extends TestCase
{
    /**
     * @var ActionButtonReader
     */
    private $actionButtonReader;

    protected function setUp(): void
    {
        $this->actionButtonReader = new ActionButtonReader(new AnnotationReader());
    }

    public function testGetActionsPresentAnnotation(): void
    {
        $actions = $this->actionButtonReader->getActions(new \ReflectionClass(AnnotationClass::class), []);

        $this->assertContains('fake_template.html.twig', reset($actions));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument "template" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\ActionButton" annotation.
     */
    public function testGetActionsException(): void
    {
        $this->actionButtonReader->getActions(new \ReflectionClass(AnnotationExceptionClass::class), []);
    }

    public function testGetActionsNoAnnotation(): void
    {
        $actions = $this->actionButtonReader->getActions(new \ReflectionClass(EmptyClass::class), []);

        $this->assertEmpty($actions);
    }
}
