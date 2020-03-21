<?php

namespace Process\Exception;

class LogicalException extends ProcessException
{
    const SELF_FLOW_CODE = 1;

    /**
     * @param string $processName
     * @param string $activityName
     * @throws LogicalException
     */
    public static function selfFlow(string $processName, string $activityName)
    {
        $message = sprintf('Detected self flow in process with name %s on activity with name %s', $processName, $activityName);
        throw new self($message, self::SELF_FLOW_CODE);
    }
}