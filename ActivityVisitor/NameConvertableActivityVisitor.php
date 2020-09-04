<?php

namespace Process\ActivityVisitor;

use Process\Context\ExecutionContext;
use Process\Contract\ActivityNameConverterInterface;
use Process\Metadata\ProcessMetadata;
use Process\Test\Unit\NameConvertableActivityVisitorTest;

/**
 * Class NameConverterActivityVisitor\
 */
class NameConvertableActivityVisitor implements ActivityVisitorInterface
{
    /**
     * @var ActivityVisitorInterface
     */
    private $activityVisitor;

    /**
     * @var ActivityNameConverterInterface
     */
    private $activityNameConverter;

    /**
     * NameConverterActivityVisitor constructor.
     * @param ActivityVisitorInterface $activityVisitor
     * @param ActivityNameConverterInterface $activityNameConverter
     */
    public function __construct(ActivityVisitorInterface $activityVisitor, ActivityNameConverterInterface $activityNameConverter)
    {
        $this->activityVisitor = $activityVisitor;
        $this->activityNameConverter= $activityNameConverter;
    }

    /**
     * @inheritdoc
     *
     * @see NameConvertableActivityVisitorTest::testVisitActivity()
     */
    public function visitActivity(ExecutionContext $executionContext, string $activityId)
    {
        $processName = $executionContext->getProcessName();
        $activityId = $this->activityNameConverter->convertNameActivity($processName, $activityId);
        $this->activityVisitor->visitActivity($executionContext, $activityId);
    }

    /**
     * @inheritdoc
     */
    public function supportNode(ProcessMetadata $processMetadata, string $nodeName): bool
    {
        return $this->activityVisitor->supportNode($processMetadata, $nodeName);
    }
}