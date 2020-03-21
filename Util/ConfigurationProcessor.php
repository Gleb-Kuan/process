<?php

namespace Process\Util;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationProcessor
{
    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * ProcessConfigurationValidator constructor.
     */
    public function __construct()
    {
        $this->optionsResolver = new OptionsResolver();
    }

    /**
     * @param array $configuration
     * @return array
     */
    public function process(array $configuration) : array
    {
        $processedConf = $this->processRootConfiguraion($configuration);
        foreach ($configuration['activities'] as $index => $activityConf) {
            $processedConf['activities'][$index] = $this->processActivityConfiguration($activityConf);
        }
        foreach ($configuration['flows'] as $index => $flowConf) {
            $processedConf['flows'][$index] = $this->processFlowConfiguration($flowConf);
        }
        return $processedConf;
    }

    /**
     * @param array $configuration
     * @return array
     */
    private function processRootConfiguraion(array $configuration) : array
    {
        return $this->optionsResolver
            ->clear()
            ->setRequired(['name', 'activities', 'flows'])
            ->resolve($configuration);
    }

    /**
     * @param array $activityConfiguration
     * @return array
     */
    private function processActivityConfiguration(array $activityConfiguration) : array
    {
        return $this->optionsResolver
            ->clear()
            ->setRequired(['name', 'activityId'])
            ->setDefault('isInitial', false)
            ->resolve($activityConfiguration);
    }

    /**
     * @param array $flowConfiguration
     * @return array
     */
    private function processFlowConfiguration(array $flowConfiguration) : array
    {
        return $this->optionsResolver
            ->clear()
            ->setRequired(['activityFromName', 'activityToName'])
            ->setDefault('isAlternative', false)
            ->resolve($flowConfiguration);
    }
}