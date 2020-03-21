<?php

namespace Process\Test;

class ProcessConfigurationDataProvider
{
    /**
     * Returns the configuration of a linear process
     *
     * @return array
     */
    public static function getLinearProcessConfiguration()
    {
        return [
            'name' => 'Test',
            'activities' => [
                [
                    'name' => 'item1',
                    'activityId' => 'item1Handler',
                    'isInitial' => true,
                ],
                [
                    'name' => 'item2',
                    'activityId' => 'item2Handler',
                ],
                [
                    'name' => 'item3',
                    'activityId' => 'item3Handler',
                ],
            ],
            'flows' => [
                [
                    'activityFromName' => 'item1',
                    'activityToName' => 'item2'
                ],
                [
                    'activityFromName' => 'item2',
                    'activityToName' => 'item3'
                ],
            ]
        ];
    }

    /**
     * Returns the configuration of the branching process
     *
     * @return array
     */
    public static function getBranchingProcessConfiguration()
    {
        return [
            'name' => 'Test',
            'activities' => [
                [
                    'name' => 'item1',
                    'activityId' => 'item1Handler',
                    'isInitial' => true,
                ],
                [
                    'name' => 'item2',
                    'activityId' => 'item2Handler',
                ],
                [
                    'name' => 'item3',
                    'activityId' => 'item3Handler',
                ],
                [
                    'name' => 'item4',
                    'activityId' => 'item4Handler',
                ],
                [
                    'name' => 'item5',
                    'activityId' => 'item5Handler',
                ],
                [
                    'name' => 'item6',
                    'activityId' => 'item6Handler',
                ],
                [
                    'name' => 'item7',
                    'activityId' => 'item7Handler',
                ],
            ],
            'flows' => [
                [
                    'activityFromName' => 'item1',
                    'activityToName' => 'item2'
                ],
                [
                    'activityFromName' => 'item2',
                    'activityToName' => 'item3'
                ],
                [
                    'activityFromName' => 'item2',
                    'activityToName' => 'item4',
                    'isAlternative' => true,
                ],
                [
                    'activityFromName' => 'item4',
                    'activityToName' => 'item5',
                ],
                [
                    'activityFromName' => 'item2',
                    'activityToName' => 'item6',
                    'isAlternative' => true,
                ],
                [
                    'activityFromName' => 'item6',
                    'activityToName' => 'item7',
                ],
            ],
        ];
    }
}