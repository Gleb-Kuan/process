<?php

namespace Process;

use Process\Metadata\ProcessMetadata;

class SchemaFactory
{
    /**
     * @param ProcessMetadata $processMetadata
     * @return SchemaItem
     */
    public function createSchema(ProcessMetadata $processMetadata) : SchemaItem
    {
        return $this->createSchemaItem($processMetadata->getInitialActivity(), $processMetadata);
    }

    /**
     * @param $activityName
     * @param ProcessMetadata $processMetadata
     * @return SchemaItem
     */
    private function createSchemaItem($activityName, ProcessMetadata $processMetadata) : SchemaItem
    {
        $activities = $processMetadata->getActivities();
        $activityData = $activities[$activityName];

        $nextSchemaItem = null;
        if(isset($activityData['nextActivity'])) {
            $nextSchemaItem = $this->createSchemaItem($activityData['nextActivity'], $processMetadata);
        }
        $alternativeNextSchemaItems = [];
        if(isset($activityData['alternativeNextActivities'])) {
            foreach ($activityData['alternativeNextActivities'] as $alternativeNextActivity) {
                $alternativeNextSchemaItems[$alternativeNextActivity] = $this->createSchemaItem($alternativeNextActivity, $processMetadata);
            }
        }
        return new SchemaItem($activityName, $activityData['activityId'], $nextSchemaItem, $alternativeNextSchemaItems);
    }
}