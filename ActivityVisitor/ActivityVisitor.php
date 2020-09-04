<?php

namespace Process\ActivityVisitor;

use Process\Context\AlternativeFlowActivityContext;
use Process\Context\ExecutionContext;
use Process\Context\StopProcessActivityContext;
use Process\Contract\ActivityInterface;
use Process\Event\AlternativeFlowEvent;
use Process\Event\SchemaItemEvent;
use Process\Event\StopSchemaEvent;
use Process\Exception\ActivityVisitorException;
use Process\Exception\AlternativeFlowException;
use Process\Exception\StopSchemaException;
use Process\Metadata\ProcessMetadata;
use Process\Test\Unit\ActivityVisitorTest;

/**
 * Class ProcessNodeVisitor
 */
class ActivityVisitor implements ActivityVisitorInterface
{
    const EVENT_BEFORE = 'event_before';
    const EVENT_AFTER = 'event_after';

    private $activityMeta = [];

    private $activityName = '';

    /**
     * @inheritdoc
     *
     * @see ActivityVisitorTest::testVisitActivity()
     * @see ActivityVisitorTest::testVisitActivityWhenIncorrectInterface()
     * @see ActivityVisitorTest::testVisitActivityWhenAlternativeFlow()
     * @see ActivityVisitorTest::testVisitActivityWhenStop()
     */
    public function visitActivity(ExecutionContext $executionContext, string $activityId)
    {
        $activity = $this->getActivity($executionContext, $activityId);
        if(!($activity instanceof ActivityInterface)) {
            ActivityVisitorException::activityDoesNotImplementRequiredInterface($activityId);
        }

        try {
            $dispatcher = $executionContext->getEventDispatcher();
            $this->onBeforeAction($executionContext);
            $activity($executionContext);
            $this->onAfterAction($executionContext);
        }
        catch (AlternativeFlowException $alternativeFlowException) {
            $dispatcher->dispatch(new AlternativeFlowEvent($executionContext, $this->activityName, $alternativeFlowException->getAlternativeActivityName()));
            throw $alternativeFlowException;
        }
        catch (StopSchemaException $stopSchemaException) {
            $dispatcher->dispatch(new StopSchemaEvent($executionContext));
            throw $stopSchemaException;
        }
    }

    /**
     * @inheritdoc
     */
    public function supportNode(ProcessMetadata $processMetadata, string $nodeName): bool
    {
        $this->activityName = $nodeName;
        $activityData = $processMetadata->getActivityData($nodeName);
        $this->activityMeta = $activityData['meta'];

        return true;
    }

    /**
     * @param ExecutionContext $executionContext
     * @param string $activityId
     * @return mixed
     *
     * @see ActivityVisitorTest::testGetActivity()
     * @see ActivityVisitorTest::testGetActivityWhenNotFound()
     */
    protected function getActivity(ExecutionContext $executionContext, string $activityId)
    {
        $container = $executionContext->getContainer();
        if(!$container->has($activityId)) {
            ActivityVisitorException::activityNotFound($activityId);
        }
        return $container->get($activityId);
    }

    /**
     * @param ExecutionContext $executionContext
     */
    protected function onBeforeAction(ExecutionContext $executionContext)
    {
        $dispatcher = $executionContext->getEventDispatcher();
        if(isset($this->activityMeta['event'][self::EVENT_BEFORE]) && $this->activityMeta['event'][self::EVENT_BEFORE]) {
            $dispatcher->dispatch(new SchemaItemEvent($executionContext, $executionContext->getCurrentSchemaItem(), SchemaItemEvent::START_ACTIVITY));
        }
    }

    /**
     * @param ExecutionContext $executionContext
     */
    protected function onAfterAction(ExecutionContext $executionContext)
    {
        $dispatcher = $executionContext->getEventDispatcher();
        if(isset($this->activityMeta['event'][self::EVENT_AFTER]) && $this->activityMeta['event'][self::EVENT_AFTER]) {
            $dispatcher->dispatch(new SchemaItemEvent($executionContext, $executionContext->getCurrentSchemaItem(), SchemaItemEvent::END_ACTIVITY));
        }
    }
}