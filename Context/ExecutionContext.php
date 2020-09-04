<?php

namespace Process\Context;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class ExecutionContext
 */
class ExecutionContext
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var string
     */
    private $processName;

    /**
     * @var mixed
     */
    private $input;

    /**
     * @var string
     */
    private $currentSchemaItem;

    /**
     * @var array
     */
    private $results;

    /**
     * ExecutionContext constructor.
     *
     * @param ContainerInterface $container
     * @param EventDispatcherInterface $eventDispatcher
     * @param string $processName
     * @param $input
     */
    public function __construct(ContainerInterface $container, EventDispatcherInterface $eventDispatcher, string $processName, $input)
    {
        $this->container = $container;
        $this->dispatcher = $eventDispatcher;
        $this->processName = $processName;
        $this->input = $input;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function getProcessName(): string
    {
        return $this->processName;
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param string $schemaItemName
     */
    public function setCurrentSchemaItem(string $schemaItemName)
    {
        $this->currentSchemaItem = $schemaItemName;
    }

    /**
     * @return null|string
     */
    public function getCurrentSchemaItem(): ?string
    {
        return $this->currentSchemaItem;
    }

    /**
     * @param ActivityContextInterface $activityContext
     */
    public function registry(ActivityContextInterface $activityContext)
    {
        $identifier = $activityContext->getIdentifier();
        $this->results[$identifier] = $activityContext;
    }

    /**
     * @param string $identifier
     * @return null|ActivityContextInterface
     */
    public function getActivityContext(string $identifier): ?ActivityContextInterface
    {
        return $this->results[$identifier] ?? null;
    }

    /**
     * @param string $identifier
     * @return bool
     */
    public function hasActivityContext(string $identifier): bool
    {
        return isset($this->results[$identifier]) ? true : false;
    }

    /**
     * @param string $identifier
     */
    public function removeActivityContext(string $identifier)
    {
        unset($this->results[$identifier]);
    }
}