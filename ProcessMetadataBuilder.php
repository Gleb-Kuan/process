<?php

namespace Process;

use Process\Exception\LogicalException;
use Process\Exception\ProcessBuildException;
use Process\Metadata\ProcessMetadata;
use Process\Util\ConfigurationProcessor;

class ProcessMetadataBuilder
{
    /**
     * @var string
     */
    private $processName;

    /**
     * @var array
     */
    private $activities = [];

    /**
     * @var array
     */
    private $flows = [];

    /**
     * @var string
     */
    private $initialActivity = '';

    /**
     * @param $processName
     */
    public function setProcessName($processName) : void
    {
        $this->processName = $processName;
    }

    /**
     * @param string $name
     * @param string $activityId
     */
    public function addSchemaItem(string $name, string $activityId) : void
    {
        $this->activities[$name] = ['activityId' => $activityId];
    }

    /**
     * @param string $activityFromName
     * @param string $activityToName
     * @param bool $isAlternative
     */
    public function addFlow(string $activityFromName, string $activityToName, $isAlternative = false) : void
    {
        if($activityFromName == $activityToName) {
            LogicalException::selfFlow($this->processName, $activityFromName);
        }
        if(!isset($this->activities[$activityFromName])) {
            ProcessBuildException::activityMissing($this->processName, $activityFromName);
        }
        if(!isset($this->activities[$activityToName])) {
            ProcessBuildException::activityMissing($this->processName, $activityToName);
        }
        $this->flows[] = [$activityFromName, $activityToName];

        if($isAlternative) {
            $this->activities[$activityFromName]['alternativeNextActivities'][] = $activityToName;
        }
        else {
            $this->activities[$activityFromName]['nextActivity'] = $activityToName;
        }
    }

    /**
     * @param string $activityName
     */
    public function setInitialActivity(string $activityName) : void
    {
        if(!isset($this->activities[$activityName])) {
            ProcessBuildException::activityMissing($this->processName, $activityName);
        }
        $this->initialActivity = $activityName;
    }

    /**
     * @return ProcessMetadata
     * @throws ProcessBuildException
     */
    public function getProcessMetadata() : ProcessMetadata
    {
        if(!$this->initialActivity) {
            ProcessBuildException::initialActivityMissing($this->initialActivity);
        }
        return new ProcessMetadata($this->processName, $this->initialActivity, $this->activities, $this->flows);
    }

    /**
     * @param array $configuration
     * @return ProcessMetadata
     */
    public function buildFromArray(array $configuration) : ProcessMetadata
    {
        $processConfigurationValidator = new ConfigurationProcessor();
        $processedConf = $processConfigurationValidator->process($configuration);

        $this->setProcessName($processedConf['name']);
        foreach ($processedConf['activities'] as $activity) {
            $this->addSchemaItem($activity['name'], $activity['activityId']);
            if($activity['isInitial']) {
                $this->setInitialActivity($activity['name']);
            }
        }
        foreach ($processedConf['flows'] as $flow) {
            $this->addFlow($flow['activityFromName'], $flow['activityToName'], $flow['isAlternative']);
        }
        return $this->getProcessMetadata();
    }
}