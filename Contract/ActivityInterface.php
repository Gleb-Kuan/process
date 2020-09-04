<?php

namespace Process\Contract;

use Process\Context\ExecutionContext;

/**
 * Interface ActivityInterface
 */
interface ActivityInterface
{
    /**
     * @param ExecutionContext $context
     * @return mixed
     */
    public function __invoke(ExecutionContext $context);

    /**
     * @param ExecutionContext $context
     * @return mixed
     */
    public function execute(ExecutionContext $context);
}