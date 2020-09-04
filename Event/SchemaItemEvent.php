<?php

namespace Process\Event;

use Process\Context\ExecutionContext;

class SchemaItemEvent extends ProcessEvent
{
    const START_ACTIVITY = 'start_activity';
    const END_ACTIVITY = 'end_acivity';

    /**
     * @var string
     */
    private $schemaItemName;

    /**
     * @var string
     */
    private $eventType;

    /**
     * SchemaItemEvent constructor.
     *
     * @param ExecutionContext $executionContext
     * @param string $schemaItemName
     * @param string $eventType
     */
    public function __construct(ExecutionContext $executionContext, string $schemaItemName, string $eventType)
    {
        parent::__construct($executionContext);
        $this->schemaItemName = $schemaItemName;
        $this->eventType = $eventType;
    }

    /**
     * @return string
     */
    public function getSchemaItemName() : string
    {
        return $this->schemaItemName;
    }

    /**
     * @return string
     */
    public function getEventType() : string
    {
        return $this->eventType;
    }
}