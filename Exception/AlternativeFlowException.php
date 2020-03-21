<?php

namespace Process\Exception;

use Throwable;

class AlternativeFlowException extends ProcessException
{
    const ALTERNATIVE_FLOW_CODE = 1;

    /**
     * @var string
     */
    private $alternativeSchemaItemName;

    /**
     * AlternativeFlowException constructor.
     * @param string $alternativeSchemaItemName
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $alternativeSchemaItemName, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->alternativeSchemaItemName = $alternativeSchemaItemName;
    }

    /**
     * @param string $alternativeSchemaItemName
     * @throws AlternativeFlowException
     */
    public static function alternativeFlow(string $alternativeSchemaItemName)
    {
        $message = sprintf('Alternative flow with name %s', $alternativeSchemaItemName);
        throw new self($alternativeSchemaItemName, $message, self::ALTERNATIVE_FLOW_CODE);
    }

    /**
     * @return string
     */
    public function getAlternativeActivityName() : string
    {
        return $this->alternativeSchemaItemName;
    }
}