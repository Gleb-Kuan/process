<?php

namespace Process\Contract;

use Process\Context\ExecutionContext;
use Psr\Log\LoggerAwareInterface;

/**
 * Interface ProcessLoggerAdapterInterface
 */
interface ProcessLoggerAdapterInterface extends LoggerAwareInterface
{
    /**
     * @param ExecutionContext $executionContext
     * @return mixed
     */
    public function logProcessContext(ExecutionContext $executionContext);
}