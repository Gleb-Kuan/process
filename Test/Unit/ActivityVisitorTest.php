<?php

namespace Process\Test\Unit;

use PHPUnit\Framework\TestCase;
use Process\ActivityVisitor\ActivityVisitor;
use Process\Context\AlternativeFlowActivityContext;
use Process\Context\DependencyContext;
use Process\Context\ExecutionContext;
use Process\Context\StopProcessActivityContext;
use Process\Contract\ActivityInterface;
use Process\Exception\ActivityVisitorException;
use Process\Exception\AlternativeFlowException;
use Process\Exception\StopSchemaException;
use Process\Test\TestedActivityThatImplementIncorrectInterface;
use Process\Test\TestedContainer;
use Psr\EventDispatcher\EventDispatcherInterface;

class ActivityVisitorTest extends TestCase
{
    /**
     * @see ActivityVisitor::visitActivity()
     */
    public function testVisitActivity()
    {
        $activity = $this->createMock(ActivityInterface::class);
        $activity
            ->expects($this->once())
            ->method('__invoke')
        ;
        $container = new TestedContainer();
        $container->set('handler', $activity);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $execContext = new ExecutionContext($container, $dispatcher,'Test', new \stdClass());

        $activityVisitor = new ActivityVisitor();
        $activityVisitor->visitActivity($execContext, 'handler');
    }

    /**
     * @see ActivityVisitor::visitActivity()
     */
    public function testVisitActivityWhenIncorrectInterface()
    {
        $activity = new TestedActivityThatImplementIncorrectInterface();
        $container = new TestedContainer();
        $container->set('handler', $activity);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $execContext = new ExecutionContext($container, $dispatcher,'Test', new \stdClass());

        $activityVisitor = new ActivityVisitor();
        $this->expectException(ActivityVisitorException::class);
        $this->expectExceptionCode(ActivityVisitorException::ACTIVITY_DOES_NOT_IMPLEMENT_REQUIRED_INTERFACE_CODE);
        $activityVisitor->visitActivity($execContext, 'handler');
    }

    /**
     * @see ActivityVisitor::visitActivity()
     */
    public function testVisitActivityWhenAlternativeFlow()
    {
        $activity = $this->createMock(ActivityInterface::class);
        $activity
            ->expects($this->once())
            ->method('__invoke')
            ->willThrowException(new AlternativeFlowException('alternative_item'))
        ;
        $container = new TestedContainer();
        $container->set('handler', $activity);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $execContext = new ExecutionContext($container, $dispatcher,'Test', new \stdClass());

        $activityVisitor = new ActivityVisitor();
        $this->expectException(AlternativeFlowException::class);
        $activityVisitor->visitActivity($execContext, 'handler');
    }

    /**
     * @see ActivityVisitor::visitActivity()
     */
    public function testVisitActivityWhenStop()
    {
        $activity = $this->createMock(ActivityInterface::class);
        $activity
            ->expects($this->once())
            ->method('__invoke')
            ->willThrowException(new StopSchemaException())
        ;
        $container = new TestedContainer();
        $container->set('handler', $activity);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $execContext = new ExecutionContext($container, $dispatcher,'Test', new \stdClass());

        $activityVisitor = new ActivityVisitor();
        $this->expectException(StopSchemaException::class);
        $activityVisitor->visitActivity($execContext, 'handler');
    }

    /**
     * @see ActivityVisitor::getActivity()
     */
    public function testGetActivityWhenNotFound()
    {
        $container = new TestedContainer();
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $execContext = new ExecutionContext($container, $dispatcher,'Test', new \stdClass());

        $activityVisitor = new ActivityVisitor();
        $this->expectException(ActivityVisitorException::class);
        $this->expectExceptionCode(ActivityVisitorException::ACTIVITY_NOT_FOUND_CODE);
        $this->invokeGetActivity($activityVisitor, $execContext, 'handler');

    }

    /**
     * @see ActivityVisitor::getActivity()
     */
    public function testGetActivity()
    {
        $activity = $this->createMock(ActivityInterface::class);
        $container = new TestedContainer();
        $container->set('handler', $activity);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $execContext = new ExecutionContext($container, $dispatcher,'Test', new \stdClass());

        $activityVisitor = new ActivityVisitor();
        $activity = $this->invokeGetActivity($activityVisitor, $execContext, 'handler');
        $this->assertInstanceOf(ActivityInterface::class, $activity);
    }

    /**
     * @param ActivityVisitor $activityVisitor
     * @param ExecutionContext $execContext
     * @param string $activity
     *
     * @return mixed
     */
    private function invokeGetActivity(ActivityVisitor $activityVisitor, ExecutionContext $execContext, string $activity)
    {
        $refl = new \ReflectionClass($activityVisitor);
        $method = $refl->getMethod('getActivity');
        $method->setAccessible(true);
        $result = $method->invoke($activityVisitor, $execContext, $activity);
        $method->setAccessible(false);
        return $result;
    }
}