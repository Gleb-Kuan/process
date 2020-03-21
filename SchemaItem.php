<?php

namespace Process;

use Process\Event\AlternativeFlowEvent;
use Process\Event\SchemaItemEvent;
use Process\Exception\AlternativeFlowException;
use Process\Exception\SchemaItemException;

class SchemaItem
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var SchemaItem[]
     */
    private $alternativeNextItems;

    /**
     * @var SchemaItem
     */
    private $nextItem;

    /**
     * @var string
     */
    private $activityId;

    /**
     * SchemaItem constructor.
     * @param string $name
     * @param string $activityId
     * @param SchemaItem|null $nextItem
     * @param iterable $alternativeNextItems
     */
    public function __construct(string $name, string $activityId, ?SchemaItem $nextItem, iterable $alternativeNextItems)
    {
        $this->name = $name;
        $this->activityId = $activityId;
        $this->nextItem = $nextItem;
        $this->alternativeNextItems = $alternativeNextItems;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param ExecutionContext $executionContext
     * @throws SchemaItemException
     */
    public function execute(ExecutionContext $executionContext)
    {
        $activity = $this->getActivity($executionContext);
        $eventDispatcher = $executionContext->getEventDispatcher();
        $executionContext->currentSchemaItem = $this->name;
        try {
            $eventDispatcher->dispatch(new SchemaItemEvent($executionContext, $this->name, SchemaItemEvent::START_ACTIVITY));
            $activity($executionContext);
            $eventDispatcher->dispatch(new SchemaItemEvent($executionContext, $this->name, SchemaItemEvent::END_ACTIVITY));

            if($this->nextItem) {
                $this->nextItem->execute($executionContext);
            }
        }
        catch (AlternativeFlowException $alternativeFlowException)  {
            $alternativeNextItemName = $alternativeFlowException->getAlternativeActivityName();
            if(isset($this->alternativeNextItems[$alternativeNextItemName])) {
                $eventDispatcher->dispatch(new AlternativeFlowEvent($executionContext, $this->name, $alternativeNextItemName));
                $alternativeNextItem = $this->alternativeNextItems[$alternativeNextItemName];
                $alternativeNextItem->execute($executionContext);
            }
            else {
                SchemaItemException::missingAlternativeSchemaItem($alternativeNextItemName);
            }
        }
    }

    /**
     * @param ExecutionContext $executionContext
     * @return ActivityInterface
     * @throws SchemaItemException
     */
    private function getActivity(ExecutionContext $executionContext)
    {
        $container = $executionContext->getContainer();
        if(!$container->has($this->activityId)) {
            SchemaItemException::activityNotFound($this->activityId);
        }
        $activity = $container->get($this->activityId);
        if(!($activity instanceof ActivityInterface)) {
            SchemaItemException::activityDoesNotImplementRequiredInterface($this->activityId);
        }
        return $activity;
    }
}