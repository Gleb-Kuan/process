<?php

namespace Process\Test\Functional;

use Process\Context\ExecutionContext;
use Process\Contract\ActivityInterface;
use Process\Exception\AlternativeFlowException;

class TestedActivity implements ActivityInterface
{
    const LEFT_BRANCH = 'left';
    const RIGHT_BRANCH = 'right';
    const MAIN_BRANCH = 'main';

    const LINEAR_PROCESS = 'linear_process';
    const BRANCHING_PROCESS = 'branching_process';

    private $branch;
    private $process;

    public function __construct(string $branch, string $process)
    {
        $this->branch = $branch;
        $this->process = $process;
    }

    public function __invoke(ExecutionContext $context)
    {
        $this->execute($context);
    }

    public function execute(ExecutionContext $context)
    {
        if($this->process == self::LINEAR_PROCESS) {
            return;
        }

        $currentItem = $context->getCurrentSchemaItem();
        if ($currentItem == 'item2') {
            if($this->branch == self::LEFT_BRANCH) {
                throw new AlternativeFlowException('item4');
            } elseif ($this->branch == self::RIGHT_BRANCH) {
                throw new AlternativeFlowException('item6');
            }
        }
    }
}