<?php

namespace Process\Test;

use Process\ActivityInterface;
use Process\ExecutionContext;

class TestActivity implements ActivityInterface
{
    public function __invoke(ExecutionContext $context) {}
}