<?php

namespace Process;

use Process\ActivityVisitor\ActivityVisitorInterface;
use Process\Metadata\ProcessMetadataFactoryInterface;

/**
 * Class ProcessManagerBuilder
 */
class ProcessManagerBuilder
{
    /**
     * @var ProcessMetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var iterable|ActivityVisitorInterface[]
     */
    private $activityVisitors = [];

    /**
     * @var array
     */
    private $config = [];

    /**
     * @param ProcessMetadataFactoryInterface $processMetadataFactory
     */
    public function setProcessMetadataFactory(ProcessMetadataFactoryInterface $processMetadataFactory)
    {
        $this->metadataFactory = $processMetadataFactory;
    }

    /**
     * @param ActivityVisitorInterface $activityVisitor
     */
    public function addActivityVisitor(ActivityVisitorInterface $activityVisitor)
    {
        $this->activityVisitors[] = $activityVisitor;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return ProcessManagerInterface
     */
    public function getProcessManager(): ProcessManagerInterface
    {
        return new ProcessManager($this->metadataFactory, $this->activityVisitors, $this->config);
    }
}