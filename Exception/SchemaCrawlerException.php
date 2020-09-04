<?php

namespace Process\Exception;

use Process\ActivityInterface;

/**
 * Class SchemaCrawlerException
 */
class SchemaCrawlerException extends ProcessException
{
    const MISSING_ALTERNATIVE_SCHEMA_ITEM_CODE = 1;
    const MISSING_VISITOR_FOR_SCHEMA_NAME_CODE = 2;

    /**
     * @param string $alternativeSchemaItemName
     * @throws SchemaCrawlerException
     */
    public static function missingAlternativeSchemaItem(string $alternativeSchemaItemName)
    {
        $message = sprintf('Missing alternative schema item with name %s', $alternativeSchemaItemName);
        throw new self($message, self::MISSING_ALTERNATIVE_SCHEMA_ITEM_CODE);
    }

    /**
     * @param string $schemaItem
     * @throws SchemaCrawlerException
     */
    public static function missingVisitorForSchemaItem(string $schemaItem)
    {
        $message = sprintf('Missing visitor for schema item with name %s', $schemaItem);
        throw new self($message, self::MISSING_VISITOR_FOR_SCHEMA_NAME_CODE);
    }
}