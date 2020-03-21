<?php

namespace Process\Exception;


class ProcessBuildException extends ProcessException
{
    const ACTIVITY_MISSING_CODE = 1;
    const INITIAL_ACTIVITY_MISSING_CODE = 2;

    /**
     * @param string $processName
     * @param string $activityName
     * @throws ProcessBuildException
     */
    public static function activityMissing(string $processName, string $activityName)
    {
        $message = sprintf('Activity with name %s missing in process with name %s', $activityName, $processName);
        throw new self($message, self::ACTIVITY_MISSING_CODE);
    }

    /**
     * @param string $processName
     * @throws ProcessBuildException
     */
    public static function initialActivityMissing(string $processName)
    {
        $message = sprintf('In process with name %s missing initial activity', $processName);
        throw new self($message, self::INITIAL_ACTIVITY_MISSING_CODE);
    }
}