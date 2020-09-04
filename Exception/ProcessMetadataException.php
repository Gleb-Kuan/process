<?php

namespace Process\Exception;

/**
 * Class ProcessMetadataException
 */
class ProcessMetadataException extends ProcessException
{
    const MISSING_DATA_FOR_ACTIVITY_CODE = 1;

    /**
     * @param string $activityName
     * @throws ProcessMetadataException
     */
    public static function missingDataForActivity(string $activityName)
    {
        $message = sprintf('Missing data for activity with name %s', $activityName);
        throw new self($message, self::MISSING_DATA_FOR_ACTIVITY_CODE);
    }
}