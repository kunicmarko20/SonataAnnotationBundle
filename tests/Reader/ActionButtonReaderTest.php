<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use KunicMarko\SonataAnnotationBundle\Reader\ActionButtonReader;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\AnnotationExceptionClass;
use KunicMarko\SonataAnnotationBundle\Tests\Fixtures\EmptyClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ActionButtonReaderTest extends TestCase
{
    /**
     * @var ActionButtonReader
     */
    private $actionButtonReader;

    protected function setUp(): void
    {
        $this->actionButtonReader = new ActionButtonReader(new AnnotationReader());
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
