<?php

namespace Process\ActivityVisitor;

use Process\Context\ExecutionContext;
use Process\Metadata\ProcessMetadata;

/**
 * Interface ActivityVisitorInterface
 */
interface ActivityVisitorInterface
{
    /**
     * @param ExecutionContext $executionContext
     * @param string $activityId
     * @return mixed
     */
    public function visitActivity(ExecutionContext $executionContext, string $activityId);

    /**
     * @param ProcessMetadata $processMetadata
     * @param string $nodeName
     * @return bool
     */
    public function supportNode(ProcessMetadata $processMetadata, string $nodeName): bool;
}