<?php

namespace Process;

use Process\Context\ExecutionContext;

/**
 * Interface ProcessInterface
 */
interface ProcessManagerInterface
{
    /**
     * @param ExecutionContext $executionContext
     * @return mixed
     */
    public function executeProcess(ExecutionContext $executionContext);
}