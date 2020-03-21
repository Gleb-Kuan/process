<?php

namespace Process\Exception;

use Process\ActivityInterface;

class SchemaItemException extends ProcessException
{
    const ACTIVITY_NOT_FOUND_CODE = 1;
    const ACTIVITY_DOES_NOT_IMPLEMENT_REQUIRED_INTERFACE_CODE = 2;
    const MISSING_ALTERNATIVE_SCHEMA_ITEM_CODE = 3;

    /**
     * @param string $activityId
     * @throws SchemaItemException
     */
    public static function activityNotFound(string $activityId)
    {
        $message = sprintf('Activity with id %s not found', $activityId);
        throw new self($message, self::ACTIVITY_NOT_FOUND_CODE);
    }

    /**
     * @param string $activityId
     * @throws SchemaItemException
     */
    public static function activityDoesNotImplementRequiredInterface(string $activityId)
    {
        $message = sprintf('Activity with id %s must implement %s', $activityId, ActivityInterface::class);
        throw new self($message, self::ACTIVITY_DOES_NOT_IMPLEMENT_REQUIRED_INTERFACE_CODE);
    }

    /**
     * @param string $alternativeSchemaItemName
     * @throws SchemaItemException
     */
    public static function missingAlternativeSchemaItem(string $alternativeSchemaItemName)
    {
        $message = sprintf('Missing alternative schema item with name %s', $alternativeSchemaItemName);
        throw new self($message, self::MISSING_ALTERNATIVE_SCHEMA_ITEM_CODE);
    }
}