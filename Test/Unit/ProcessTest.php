<?php

namespace Process\Test\Unit;

use PHPUnit\Framework\TestCase;
use Process\Event\InitialProcessEvent;
use Process\Process;
use Process\SchemaItem;
use Process\Test\TestContainer;
use Process\Test\TestEventDispatcher;

class ProcessTest extends TestCase
{
    public function testProcessPower()
    {
        $schemaMock = $this->getMockBuilder(SchemaItem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $schemaMock
            ->expects($this->once())
            ->method('execute');

        $containerMock = $this->getMockBuilder(TestContainer::class)
            ->getMock();

        $dispatcherMock = $this->getMockBuilder(TestEventDispatcher::class)
            ->getMock();
        $dispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(InitialProcessEvent::class));

        $process = new Process($containerMock, $dispatcherMock, $schemaMock);
        $process->evaluate([]);
    }
}