<?php

namespace Process\Event;

use Process\Context\ExecutionContext;

/**
 * Class ExecuteProcessEvent
 */
class ExecuteProcessEvent extends ProcessEvent
{
    const PROCESS_INIT = 'process_init';
    const PROCESS_END = 'process_end';

    /**
     * @var string
     */
    private $type;

    /**
     * ExecuteProcessEvent constructor.
     *
     * @param ExecutionContextInterface $executionContext
     * @param string $type
     */
    public function __construct(ExecutionContext $executionContext, string $type)
    {
        $this->type = $type;
        parent::__construct($executionContext);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}