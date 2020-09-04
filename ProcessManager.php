<?php

namespace Process;

use Process\ActivityVisitor\ActivityVisitor;
use Process\ActivityVisitor\ActivityVisitorInterface;
use Process\Context\ExecutionContext;
use Process\Contract\ProcessConfigurationProviderInterface;
use Process\Event\ExecuteProcessEvent;
use Process\Metadata\ProcessMetadata;
use Process\Metadata\ProcessMetadataFactoryInterface;
use Process\Test\Unit\ProcessManagerTest;

/**
 * Class ProcessConfigurator
 */
class ProcessManager implements ProcessManagerInterface
{
    const EVENT_BEFORE = 'event_before';
    const EVENT_AFTER = 'event_after';

    /**
     * @var array|ActivityVisitorInterface[]
     */
    private $activityVisitors;

    /**
     * @var ProcessMetadataFactoryInterface
     */
    private $processMetadataFactory;

    /**
     * @var array
     */
    private $config;

    /**
     * ProcessManager constructor.
     *
     * @param ProcessMetadataFactoryInterface $processMetadataFactory
     * @param array $activityVisitors
     * @param array $config
     */
    public function __construct(ProcessMetadataFactoryInterface $processMetadataFactory, array $activityVisitors = [], array $config = [])
    {
        $this->processMetadataFactory = $processMetadataFactory;
        $this->activityVisitors = $activityVisitors;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function executeProcess(ExecutionContext $executionContext)
    {
        $processName = $executionContext->getProcessName();
        $processMetadata = $this->processMetadataFactory->createMetadataForProcess($processName);
        $this->buildActivityVisitors();
        $schemaCrawler = $this->getSchemaCrawler($processMetadata);

        $this->onBeforeExecute($executionContext);
        $schemaCrawler->start($executionContext);
        $this->onAfterExecute($executionContext);
    }

    protected function buildActivityVisitors()
    {
        if (empty($this->activityVisitors)) {
            $defaultActivityVisitor = new ActivityVisitor();
            $this->activityVisitors[] = $defaultActivityVisitor;
        }
    }

    protected function getSchemaCrawler(ProcessMetadata $processMetadata): SchemaCrawler
    {
        return new SchemaCrawler($this->activityVisitors, $processMetadata);
    }

    /**
     * @param ExecutionContext $executionContext
     *
     * @see ProcessManagerTest::testOnBeforeExecute()
     */
    protected function onBeforeExecute(ExecutionContext $executionContext)
    {
        $dispatcher = $executionContext->getEventDispatcher();
        if(isset($this->config[self::EVENT_BEFORE]) && $this->config[self::EVENT_BEFORE]) {
            $dispatcher->dispatch(new ExecuteProcessEvent($executionContext, ExecuteProcessEvent::PROCESS_INIT));
        }
    }

    /**
     * @param ExecutionContext $executionContext
     *
     * @see ProcessManagerTest::testOnAfterExecute()
     */
    protected function onAfterExecute(ExecutionContext $executionContext)
    {
        $dispatcher = $executionContext->getEventDispatcher();
        if(isset($this->config[self::EVENT_AFTER]) && $this->config[self::EVENT_AFTER]) {
            $dispatcher->dispatch(new ExecuteProcessEvent($executionContext, ExecuteProcessEvent::PROCESS_END));
        }
    }
}