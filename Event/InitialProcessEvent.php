<?php

namespace Process\Event;

use Process\ExecutionContext;

class InitialProcessEvent extends ProcessEvent
{
    /**
     * InitialProcessEvent constructor.
     * @param ExecutionContext $executionContext
     */
    public function __construct(ExecutionContext $executionContext)
    {
        parent::__construct($executionContext);
    }
}