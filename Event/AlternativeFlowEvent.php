<?php

namespace Process\Event;

use Process\ExecutionContext;

class AlternativeFlowEvent extends ProcessEvent
{
    /**
     * @var string
     */
    private $schemaItemFromName;

    /**
     * @var string
     */
    private $schemaItemToName;

    /**
     * AlternativeFlowEvent constructor.
     * @param ExecutionContext $executionContext
     * @param string $schemaItemFromName
     * @param string $schemaItemToName
     */
    public function __construct(ExecutionContext $executionContext, string $schemaItemFromName, string $schemaItemToName)
    {
        parent::__construct($executionContext);
        $this->schemaItemFromName = $schemaItemFromName;
        $this->schemaItemToName = $schemaItemToName;
    }

    /**
     * @return string
     */
    public function getSchemaItemFromName() : string
    {
        return $this->schemaItemFromName;
    }

    /**
     * @return string
     */
    public function getSchemaItemToName() : string
    {
        return $this->schemaItemToName;
    }
}