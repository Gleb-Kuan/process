<?php

namespace Process\Event;

use Process\Context\ExecutionContext;

/**
 * Class ProcessEvent
 */
class ProcessEvent
{
    /**
     * @var ExecutionContextInterface
     */
    protected $executionContext;

    /**
     * ProcessEvent constructor.
     *
     * @param ExecutionContextInterface $executionContext
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