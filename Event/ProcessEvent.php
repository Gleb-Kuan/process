<?php

namespace Process\Event;

use Process\ExecutionContext;

class ProcessEvent
{
    /**
     * @var ExecutionContext
     */
    protected $executionContext;

    /**
     * ProcessEvent constructor.
     * @param ExecutionContext $executionContext
     */
    public function __construct(ExecutionContext $executionContext)
    {
        $this->executionContext = $executionContext;
    }

    /**
     * @return ExecutionContext
     */
    public function getExecutionContext() : ExecutionContext
    {
        return $this->executionContext;
    }
}