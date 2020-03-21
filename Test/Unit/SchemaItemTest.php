<?php

namespace Process\Test\Unit;

use Process\Exception\AlternativeFlowException;
use Process\Exception\SchemaItemException;
use Process\ExecutionContext;
use Process\SchemaItem;
use Process\Test\ProcessBaseTest;
use Process\Test\TestActivity;
use Process\Test\TestActivityThatImplementIncorrectInterface;

/**
 * Class SchemaItemTest
 */
class SchemaItemTest extends ProcessBaseTest
{
    /**
     * Testing case when called next activity.
     */
    public function testCallNextItem()
    {
        $activityMock = $this->getMockBuilder(TestActivity::class)
            ->getMock();
        $activityMock
            ->expects($this->once())
            ->method('__invoke');

        $dependency = $this->getExternalDependency();
        $dependency['container']->set('item1Handler', $activityMock);

        $nextItemMock = $this->getMockBuilder(SchemaItem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nextItemMock
            ->expects($this->once())
            ->method('execute');

        $alternativeMock = $this->getMockBuilder(SchemaItem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $alternativeMock
            ->expects($this->never())
            ->method('execute');

        $executionContext = new ExecutionContext($dependency['container'], $dependency['eventDispatcher'], [], 'Test');
        $schemaItem = new SchemaItem('callNextItem', 'item1Handler', $nextItemMock, ['item2' => $alternativeMock]);

        $schemaItem->execute($executionContext);
    }

    /**
     * Testing case when called alternative activity.
     */
    public function testCallAlternativeItem()
    {
        $dependency = $this->getExternalDependency();
        $activityMock = $this->getMockBuilder(TestActivity::class)
            ->getMock();
        $activityMock
            ->expects($this->once())
            ->method('__invoke')
            ->will($this->returnCallback(
                function (ExecutionContext $executionContext){
                    AlternativeFlowException::alternativeFlow('item3');
                })
            );

        $alternativeMock = $this->getMockBuilder(SchemaItem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $alternativeMock
            ->expects($this->once())
            ->method('execute');

        $dependency['container']->set('item1Handler', $activityMock);

        $executionContext = new ExecutionContext($dependency['container'], $dependency['eventDispatcher'], [], 'Test');
        $schemaItem = new SchemaItem('callNextItem', 'item1Handler', null, ['item3' => $alternativeMock]);

        $schemaItem->execute($executionContext);
    }

    /**
     * Testing case when called undefined alternative activity.
     */
    public function testCallUndefinedAlternativeItem()
    {
        $dependency = $this->getExternalDependency();
        $activityMock = $this->getMockBuilder(TestActivity::class)
            ->getMock();
        $activityMock
            ->expects($this->once())
            ->method('__invoke')
            ->will($this->returnCallback(
                function (ExecutionContext $executionContext){
                    AlternativeFlowException::alternativeFlow('item3');
                })
            );

        $dependency['container']->set('item1Handler', $activityMock);

        $executionContext = new ExecutionContext($dependency['container'], $dependency['eventDispatcher'], [], 'Test');
        $schemaItem = new SchemaItem('callNextItem', 'item1Handler', null, []);

        $this->expectException(SchemaItemException::class);
        $schemaItem->execute($executionContext);
    }

    /**
     * Testing case when activity not found.
     */
    public function testActivityNotFound()
    {
        $dependency = $this->getExternalDependency();
        $executionContext = new ExecutionContext($dependency['container'], $dependency['eventDispatcher'], [], 'Test');
        $schemaItem = new SchemaItem('callNextItem', 'item1Handler', null, []);

        $this->expectException(SchemaItemException::class);
        $this->expectExceptionCode(SchemaItemException::ACTIVITY_NOT_FOUND_CODE);
        $schemaItem->execute($executionContext);
    }

    /**
     * Testing case when activity implement incorrect interface.
     */
    public function testActivityImplementIncorrectInterface()
    {
        $dependency = $this->getExternalDependency();
        $activityThatImplementIncorrectInterface = new TestActivityThatImplementIncorrectInterface();
        $dependency['container']->set('item1Handler', $activityThatImplementIncorrectInterface);

        $executionContext = new ExecutionContext($dependency['container'], $dependency['eventDispatcher'], [], 'Test');
        $schemaItem = new SchemaItem('callNextItem', 'item1Handler', null, []);

        $this->expectException(SchemaItemException::class);
        $this->expectExceptionCode(SchemaItemException::ACTIVITY_DOES_NOT_IMPLEMENT_REQUIRED_INTERFACE_CODE);
        $schemaItem->execute($executionContext);
    }
}