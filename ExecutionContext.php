<?php

namespace Process;

use Process\Event\InitialProcessEvent;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class ExecutionContext
{
    /**
     * @var ExecutionResults
     */
    public $executionResults;

    /**
     * @var string
     */
    public $currentSchemaItem;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var mixed
     */
    private $input;

    /**
     * @var string
     */
    private $processName;

    /**
     * ExecutionContext constructor.
     * @param ContainerInterface $container
     * @param EventDispatcherInterface $eventDispatcher
     * @param string $processName
     */
    public function __construct(ContainerInterface $container, EventDispatcherInterface $eventDispatcher, $input, string $processName = '')
    {
        $this->container = $container;
        $this->eventDispatcher = $eventDispatcher;
        $this->input = $input;
        $this->processName = $processName;

        $executionResults = new ExecutionResults();
        $this->eventDispatcher->dispatch(new InitialProcessEvent($this));
        $this->executionResults = $executionResults;
    }

    /**
     * @return string
     */
    public function getProcessName() : string
    {
        return $this->processName;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer() : ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher() : EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * @return ExecutionResults
     */
    public function getExecutionResults() : ExecutionResults
    {
        return $this->executionResults;
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }
}

class ExecutionResults implements \ArrayAccess
{
    /**
     * @var array
     */
    private $results = [];

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->results[$offset]) ? true : false;
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->results[$offset] : null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->results[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->results[$offset]);
    }
}