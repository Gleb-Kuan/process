<?php

namespace Process;

use Process\ActivityVisitor\ActivityVisitorInterface;
use Process\Context\ExecutionContext;
use Process\Exception\AlternativeFlowException;
use Process\Exception\SchemaCrawlerException;
use Process\Exception\StopSchemaException;
use Process\Metadata\ProcessMetadata;

/**
 * Class SchemaCrawler
 */
class SchemaCrawler
{
    /**
     * @var iterable|ActivityVisitorInterface[]
     */
    private $activityVisitors;

    /**
     * @var ProcessMetadata
     */
    private $processMetadata;

    /**
     * @var ExecutionContext
     */
    private $executionContext;

    /**
     * SchemaCrawler constructor.
     *
     * @param iterable|ActivityVisitorInterface[] $activityVisitors
     * @param ProcessMetadata $processMetadata
     */
    public function __construct(
        iterable $activityVisitors,
        ProcessMetadata $processMetadata)
    {
        $this->activityVisitors = $activityVisitors;
        $this->processMetadata = $processMetadata;
    }

    /**
     * @param ExecutionContext $executionContext
     */
    public function start(ExecutionContext $executionContext)
    {
        $this->executionContext = $executionContext;
        $initialActivity = $this->processMetadata->getInitialActivity();
        $this->onNode($initialActivity);
    }

    /**
     * @param string $itemName
     */
    protected function onNode(string $itemName)
    {
        $activityData = $this->processMetadata->getActivityData($itemName);

        try {
            $this->executionContext->setCurrentSchemaItem($itemName);
            $activityVisitor = $this->getVisitorForNode($itemName);
            $activityVisitor->visitActivity($this->executionContext, $activityData['activityId']);
            if(isset($activityData['nextActivity'])) {
                $this->onNode($activityData['nextActivity']);
            }
        }

        catch (AlternativeFlowException $alternativeFlowException)  {
            $this->executeAlternativeItem($alternativeFlowException, $activityData);
        }

        catch (StopSchemaException $stopProcessException) {}
    }

    /**
     * @param string $itemName
     * @return ActivityVisitorInterface
     */
    private function getVisitorForNode(string $itemName): ActivityVisitorInterface
    {
        $activityVisitor = null;
        foreach ($this->activityVisitors as $currentActivityVisitor) {
            if($currentActivityVisitor->supportNode($this->processMetadata, $itemName)) {
                $activityVisitor = $currentActivityVisitor;
                break;
            }
        }
        if(!$activityVisitor) {
            SchemaCrawlerException::missingVisitorForSchemaItem($itemName);
        }

        return $activityVisitor;
    }

    /**
     * @param AlternativeFlowException $alternativeFlowException
     * @param array $activityData
     */
    private function executeAlternativeItem(AlternativeFlowException $alternativeFlowException, array $activityData)
    {
        $alternativeNextItemName = $alternativeFlowException->getAlternativeActivityName();
        if(isset($activityData['alternativeNextActivities']) && in_array($alternativeNextItemName, $activityData['alternativeNextActivities'])) {
            $this->onNode($alternativeNextItemName);
        }
        else {
            SchemaCrawlerException::missingAlternativeSchemaItem($alternativeNextItemName);
        }
    }
}