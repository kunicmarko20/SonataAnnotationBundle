<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Tests\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use InvalidArgumentException;
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

    public function testGetActionsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument "template" is mandatory in "KunicMarko\SonataAnnotationBundle\Annotation\ActionButton" annotation.');

        $this->actionButtonReader->getActions(new \ReflectionClass(AnnotationExceptionClass::class), []);
    }

    public function testGetActionsNoAnnotation(): void
    {
        $actions = $this->actionButtonReader->getActions(new \ReflectionClass(EmptyClass::class), []);

        $this->assertEmpty($actions);
    }
}
