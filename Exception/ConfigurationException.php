<?php

namespace Process\Exception;

/**
 * Class ConfigurationException
 */
class ConfigurationException extends ProcessException
{
    public static function missingRequiredField(string $field)
    {

    }
    
    public static function activityMustHaveName(array $activityData)
    {
        
    }
    
    public static function missingActivityFromName(array $flowData)
    {
        
    }
    
    public static function missingActivityToName(array $flowData)
    {
        
    }
}