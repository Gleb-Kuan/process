<?php

namespace Process\Test\Functional;

use Process\Metadata\ProcessMetadata;
use Process\Test\ProcessBaseTest;
use Process\Test\ProcessConfigurationDataProvider;

class ProcessMetadataBuilderFunctionalTest extends ProcessBaseTest
{
    /**
     * Linear process metadata testing
     */
    public function testBuildLinearProcessMetadata()
    {
        $configuration = ProcessConfigurationDataProvider::getLinearProcessConfiguration();
        $processMetadata = $this->createProcessMetadata($configuration);

        $this->assertEquals($processMetadata->getInitialActivity(), 'item1');

        $this->assertTrue($this->hasActivity($processMetadata, 'item1'));
        $this->assertTrue($this->isNextActivity($processMetadata, 'item1', 'item2'));
        $this->assertFalse($this->hasAlternativeActivities($processMetadata, 'item1'));

        $this->assertTrue($this->hasActivity($processMetadata, 'item2'));
        $this->assertTrue($this->isNextActivity($processMetadata, 'item2', 'item3'));
        $this->assertFalse($this->hasAlternativeActivities($processMetadata, 'item2'));

        $this->assertTrue($this->hasActivity($processMetadata, 'item3'));
        $this->assertFalse($this->isNextActivity($processMetadata, 'item3', 'item4'));
        $this->assertFalse($this->hasAlternativeActivities($processMetadata, 'item3'));

        $this->assertFalse($this->hasActivity($processMetadata, 'item4'));
    }

    /**
     * Branching process metadata testing
     */
    public function testBuildBranchingProcessMetadata()
    {
        $configuration = ProcessConfigurationDataProvider::getBranchingProcessConfiguration();
        $processMetadata = $this->createProcessMetadata($configuration);

        $this->assertEquals($processMetadata->getInitialActivity(), 'item1');

        // main part
        $this->assertTrue($this->hasActivity($processMetadata, 'item1'));
        $this->assertTrue($this->isNextActivity($processMetadata, 'item1', 'item2'));
        $this->assertFalse($this->hasAlternativeActivities($processMetadata, 'item1'));

        $this->assertTrue($this->hasActivity($processMetadata, 'item2'));
        $this->assertTrue($this->isNextActivity($processMetadata, 'item2', 'item3'));
        $this->assertTrue($this->hasAlternativeActivities($processMetadata, 'item2'));
        $this->assertTrue($this->hasAlternativeActivity($processMetadata, 'item2', 'item4'));
        $this->assertTrue($this->hasAlternativeActivity($processMetadata, 'item2', 'item6'));

        $this->assertTrue($this->hasActivity($processMetadata, 'item3'));
        $this->assertFalse($this->hasNextActivity($processMetadata, 'item3'));
        $this->assertFalse($this->hasAlternativeActivities($processMetadata, 'item3'));

        // branching
        $this->assertTrue($this->hasActivity($processMetadata, 'item4'));
        $this->assertTrue($this->isNextActivity($processMetadata, 'item4', 'item5'));
        $this->assertFalse($this->hasAlternativeActivities($processMetadata, 'item4'));

        $this->assertTrue($this->hasActivity($processMetadata, 'item5'));
        $this->assertFalse($this->hasNextActivity($processMetadata, 'item5'));
        $this->assertFalse($this->hasAlternativeActivities($processMetadata, 'item5'));

        // branching
        $this->assertTrue($this->hasActivity($processMetadata, 'item6'));
        $this->assertTrue($this->isNextActivity($processMetadata, 'item6', 'item7'));
        $this->assertFalse($this->hasAlternativeActivities($processMetadata, 'item6'));

        $this->assertTrue($this->hasActivity($processMetadata, 'item7'));
        $this->assertFalse($this->hasNextActivity($processMetadata, 'item7'));
        $this->assertFalse($this->hasAlternativeActivities($processMetadata, 'item7'));
    }

    /**
     * @param ProcessMetadata $processMetadata
     * @param string $activityName
     * @return bool
     */
    private function hasActivity(ProcessMetadata $processMetadata, string $activityName) : bool
    {
        foreach ($processMetadata->getActivities() as $name => $activity) {
            if($name == $activityName) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param ProcessMetadata $processMetadata
     * @param string $activityName
     * @return bool
     */
    private function hasNextActivity(ProcessMetadata $processMetadata, string $activityName) : bool
    {
        foreach ($processMetadata->getActivities() as $name => $activity) {
            if($name == $activityName && isset($activity['nextActivity'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param ProcessMetadata $processMetadata
     * @param string $activityName
     * @param string $nextActivityName
     * @return bool
     */
    private function isNextActivity(ProcessMetadata $processMetadata, string $activityName, string $nextActivityName) : bool
    {
        foreach ($processMetadata->getActivities() as $name => $activity) {
            if($name == $activityName && isset($activity['nextActivity'])) {
                return $activity['nextActivity'] == $nextActivityName;
            }
        }
        return false;
    }

    /**
     * @param ProcessMetadata $processMetadata
     * @param string $activityName
     * @return bool
     */
    private function hasAlternativeActivities(ProcessMetadata $processMetadata, string $activityName) : bool
    {
        foreach ($processMetadata->getActivities() as $name => $activity) {
            if($name == $activityName && isset($activity['alternativeNextActivities'])) {
                return count($activity['alternativeNextActivities']) != 0;
            }
        }
        return false;
    }

    /**
     * @param ProcessMetadata $processMetadata
     * @param string $activityName
     * @param string $alternativeActivityName
     * @return bool
     */
    private function hasAlternativeActivity(ProcessMetadata $processMetadata, string $activityName, string $alternativeActivityName) : bool
    {
        foreach ($processMetadata->getActivities() as $name => $activity) {
            if($name == $activityName && isset($activity['alternativeNextActivities'])) {
                foreach ($activity['alternativeNextActivities'] as $alternativeNextActivity) {
                    if($alternativeNextActivity == $alternativeActivityName) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}