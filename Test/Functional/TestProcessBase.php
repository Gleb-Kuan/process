<?php

namespace Process\Test\Functional;

use Process\ActivityVisitor\ActivityVisitor;
use Process\Context\ExecutionContext;
use Process\Contract\ActivityInterface;
use Process\SchemaMetadataBuilder;
use PHPUnit\Framework\TestCase;
use Process\Test\ProcessConfigurationDataProvider;
use Process\Test\TestedContainer;
use Psr\EventDispatcher\EventDispatcherInterface;

class TestProcessBase extends TestCase
{
    /**
     * @param ActivityInterface $testActivity
     *
     * @return ExecutionContext
     */
    protected function prepareExecutionContext(ActivityInterface $testActivity): ExecutionContext
    {
        $container = new TestedContainer();
        $container->set('item1Handler', $testActivity);
        $container->set('item2Handler', $testActivity);
        $container->set('item3Handler', $testActivity);
        $container->set('item4Handler', $testActivity);
        $container->set('item5Handler', $testActivity);
        $container->set('item6Handler', $testActivity);
        $container->set('item7Handler', $testActivity);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $name = 'Test';
        $input = new \stdClass();
        return new ExecutionContext($container, $eventDispatcher, $name, $input);
    }

    /**
     * @return TestedSchemaCrawler
     */
    protected function prepareSchemaCrawler(): TestedSchemaCrawler
    {
        $activityVisitor = new ActivityVisitor();
        $config = ProcessConfigurationDataProvider::getBranchingProcessConfiguration();
        $metadataBuilder = new SchemaMetadataBuilder();
        $metadata = $metadataBuilder->buildFromArray($config);

        return new TestedSchemaCrawler([$activityVisitor], $metadata);
    }
}