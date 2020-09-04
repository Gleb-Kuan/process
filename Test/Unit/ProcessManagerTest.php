<?php

namespace Process\Test\Unit;

use PHPUnit\Framework\TestCase;
use Process\Context\DependencyContext;
use Process\Context\ExecutionContext;
use Process\Event\ExecuteProcessEvent;
use Process\Metadata\ProcessMetadataFactoryInterface;
use Process\ProcessManager;
use Psr\EventDispatcher\EventDispatcherInterface;

class ProcessManagerTest extends TestCase
{
    /**
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @dataProvider dataForBeforeExecute
     */
    public function testOnBeforeExecute(EventDispatcherInterface $eventDispatcher, $config)
    {
        $this->onExecute($eventDispatcher, $config, 'onBeforeExecute');
    }

    /**
     * @return array
     */
    public function dataForBeforeExecute()
    {
        return $this->dataForOnExecute(ExecuteProcessEvent::PROCESS_INIT, ProcessManager::EVENT_BEFORE);
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param $config
     *
     * @dataProvider dataForAfterExecute
     */
    public function testOnAfterExecute(EventDispatcherInterface $eventDispatcher, $config)
    {
        $this->onExecute($eventDispatcher, $config, 'onAfterExecute');
    }

    /**
     * @return array
     */
    public function dataForAfterExecute()
    {
        return $this->dataForOnExecute(ExecuteProcessEvent::PROCESS_END, ProcessManager::EVENT_AFTER);
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param $config
     * @param string $method
     */
    private function onExecute(EventDispatcherInterface $eventDispatcher, $config, string $method)
    {
        $execContext = $this->buildExecutionContext($eventDispatcher);

        $processMetadataFactory = $this->createMock(ProcessMetadataFactoryInterface::class);
        $manager = new ProcessManager($processMetadataFactory, [], []);
        $refl = new \ReflectionClass($manager);
        $prop = $refl->getProperty('config');
        $prop->setAccessible(true);
        $method = $refl->getMethod($method);
        $method->setAccessible(true);

        $prop->setValue($manager, $config);
        $method->invoke($manager, $execContext);
    }

    /**
     * @param string $executeProcessEventType
     * @param string $managerEvent
     *
     * @return array
     */
    private function dataForOnExecute(string $executeProcessEventType, string $managerEvent)
    {
        $eventDispatcherForCalling = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcherForCalling
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->callback(function($event) use ($executeProcessEventType) {
                    return $event->getType() == $executeProcessEventType;
                })
            )
        ;
        $eventDispatcherNotCalling = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcherNotCalling
            ->expects($this->never())
            ->method('dispatch')
        ;
        return [
            'calling' => [
                $eventDispatcherForCalling,
                [
                    $managerEvent => true
                ]
            ],
            'not_calling' => [
                $eventDispatcherNotCalling,
                [
                    $managerEvent => false
                ]
            ]
        ];
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return ExecutionContext
     */
    private function buildExecutionContext(EventDispatcherInterface $eventDispatcher): ExecutionContext
    {
        $execContext = $this->getMockBuilder(ExecutionContext::class)->disableOriginalConstructor()->getMock();
        $execContext
            ->expects($this->once())
            ->method('getEventDispatcher')
            ->willReturn($eventDispatcher)
        ;

        return $execContext;
    }
}