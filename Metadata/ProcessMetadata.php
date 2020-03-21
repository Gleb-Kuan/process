<?php

namespace Process\Metadata;

final class ProcessMetadata
{
    /**
     * @var string
     */
    private $processName;

    /**
     * @var string
     */
    private $initialActivity;

    /**
     * @var iterable
     */
    private $activities;

    /**
     * @var iterable
     */
    public $flows;

    /**
     * BusinessProcessMetadata constructor.
     * @param string $processName
     * @param string $initialActivity
     * @param iterable $activities
     * @param iterable $transitions
     */
    public function __construct(string $processName, string $initialActivity, iterable $activities, iterable $flows)
    {
        $this->processName = $processName;
        $this->initialActivity = $initialActivity;
        $this->activities = $activities;
        $this->flows = $flows;
    }

    /**
     * @return string
     */
    public function getProcessName() : string
    {
        return $this->processName;
    }

    /**
     * @return string
     */
    public function getInitialActivity() : string
    {
        return $this->initialActivity;
    }

    /**
     * @return iterable
     */
    public function getActivities() : iterable
    {
        return $this->activities;
    }

    /**
     * @return iterable
     */
    public function getFlows() : iterable
    {
        return $this->flows;
    }
}