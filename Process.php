<?php

namespace Process;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class Process
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var SchemaItem
     */
    private $initialSchemaItem;

    /**
     * @var string
     */
    private $processName;

    /**
     * Process constructor.
     * @param ContainerInterface $container
     * @param EventDispatcherInterface $eventDispatcher
     * @param SchemaItem $initialSchemaItem
     */
    public function __construct(ContainerInterface $container, EventDispatcherInterface $eventDispatcher, SchemaItem $initialSchemaItem, string $processName = '')
    {
        $this->container = $container;
        $this->eventDispatcher = $eventDispatcher;
        $this->initialSchemaItem = $initialSchemaItem;
        $this->processName = $processName;
    }

    /**
     * @param $input
     * @return ExecutionResults
     */
    public function evaluate($input) : ExecutionResults
    {
        $executionContext = new ExecutionContext($this->container, $this->eventDispatcher, $input, $this->processName);
        $this->initialSchemaItem->execute($executionContext);

        return $executionContext->executionResults;
    }
}