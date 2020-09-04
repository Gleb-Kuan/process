<?php

namespace Process\Util;

use Process\Exception\ConfigurationException;

/**
 * Class ConfigurationProcessor
 */
class ConfigurationProcessor
{
    /**
     * @param array $configuration
     * @return array
     */
    public function process(array $configuration) : array
    {
        $this->processRootConfiguraion($configuration);
        foreach ($configuration['activities'] as $index => &$activityConf) {
            $this->processActivityConfiguration($activityConf);
        }
        foreach ($configuration['flows'] as $index => &$flowConf) {
            $this->processFlowConfiguration($flowConf);
        }

        return $configuration;
    }

    /**
     * @param array $configuration
     */
    private function processRootConfiguraion(array $configuration)
    {
        if(!isset($configuration['name'])) {
            ConfigurationException::missingRequiredField('name');
        }
        if(!isset($configuration['activities'])) {
            ConfigurationException::missingRequiredField('activities');
        }
        if(!isset($configuration['flows'])) {
            ConfigurationException::missingRequiredField('flows');
        }
    }

    /**
     * @param array $activityConfiguration
     */
    private function processActivityConfiguration(array &$activityConfiguration)
    {
        if(!isset($activityConfiguration['name'])) {
            ConfigurationException::activityMustHaveName($activityConfiguration);
        }
        if(!isset($activityConfiguration['isInitial'])) {
            $activityConfiguration['isInitial'] = false;
        }
        if(!isset($activityConfiguration['meta'])) {
            $activityConfiguration['meta'] = [];
        }
    }

    /**
     * @param array $flowConfiguration
     */
    private function processFlowConfiguration(array &$flowConfiguration)
    {
        if(!isset($flowConfiguration['activityFromName'])) {
            ConfigurationException::missingActivityFromName($flowConfiguration);
        }
        if(!isset($flowConfiguration['activityToName'])) {
            ConfigurationException::missingActivityToName($flowConfiguration);
        }
        if(!isset($flowConfiguration['isAlternative'])) {
            $flowConfiguration['isAlternative'] = false;
        }
    }
}