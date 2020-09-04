<?php

namespace Process\Contract;

/**
 * Interface ActivityNameConverterInterface
 */
interface ActivityNameConverterInterface
{
    /**
     * @param string $processName
     * @param string $activityName
     * @return string
     */
    public function convertNameActivity(string $processName, string $activityName): string;
}