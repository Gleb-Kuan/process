<?php

namespace Process\Exception;

/**
 * Class ActivityVisitorException
 */
class ActivityVisitorException extends ProcessException
{
    const ACTIVITY_NOT_FOUND_CODE = 1;
    const ACTIVITY_DOES_NOT_IMPLEMENT_REQUIRED_INTERFACE_CODE = 2;

    /**
     * @param string $activityId
     *
     * @throws ActivityVisitorException
     */
    public static function activityNotFound(string $activityId)
    {
        $message = sprintf('Activity with id %s not found', $activityId);
        throw new self($message, self::ACTIVITY_NOT_FOUND_CODE);
    }

    /**
     * @param string $activityId
     *
     * @throws ActivityVisitorException
     */
    public static function activityDoesNotImplementRequiredInterface(string $activityId)
    {
        $message = sprintf('Activity with id %s must implement %s', $activityId, ActivityInterface::class);
        throw new self($message, self::ACTIVITY_DOES_NOT_IMPLEMENT_REQUIRED_INTERFACE_CODE);
    }
}