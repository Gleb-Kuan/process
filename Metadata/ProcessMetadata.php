<?php

namespace Process\Metadata;

use Process\Exception\ProcessMetadataException;
use Process\Test\Unit\ProcessMetadataTest;

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
     *
     * @see ProcessMetadataTest::testAllMethods()
     */
    public function getProcessName() : string
    {
        return $this->processName;
    }

    /**
     * @return string
     *
     * @see ProcessMetadataTest::testAllMethods()
     */
    public function getInitialActivity() : string
    {
        return $this->initialActivity;
    }

    /**
     * @return iterable
     *
     * @see ProcessMetadataTest::testAllMethods()
     */
    public function getActivities() : iterable
    {
        return $this->activities;
    }

    /**
     * @return iterable
     *
     * @see ProcessMetadataTest::testAllMethods()
     */
    public function getFlows() : iterable
    {
        return $this->flows;
    }

    /**
     * @param string $name
     * @return mixed
     *
     * @see ProcessMetadataTest::testAllMethods()
     */
    public function getActivityData(string $name)
    {
        $activities = $this->getActivities();
        if(!isset($activities[$name])) {
            ProcessMetadataException::missingDataForActivity($name);
        }
        return $activities[$name];
    }
}