<?php

namespace Process\Exception;

/**
 * Class StopSchemaException
 */
class StopSchemaException extends ProcessException
{
    const STOP_SCHEMA_CODE = 1;

    /**
     * @param string $processName
     *
     * @throws StopSchemaException
     */
    public static function stopSchema(string $processName)
    {
        throw new self(sprintf('Stop process %s', $processName), self::STOP_SCHEMA_CODE);
    }
}