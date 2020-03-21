<?php

namespace Process;

interface ActivityInterface
{
    /**
     * @param ExecutionContext $context
     * @return mixed
     */
    public function __invoke(ExecutionContext $context);
}