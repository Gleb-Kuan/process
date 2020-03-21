<?php

namespace Process\Test;

use Psr\EventDispatcher\EventDispatcherInterface;

class TestEventDispatcher implements EventDispatcherInterface
{
    /**
     * @param object $event
     */
    public function dispatch(object $event) {}
}