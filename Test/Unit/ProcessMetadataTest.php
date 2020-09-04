<?php

namespace Process\Test\Unit;

use PHPUnit\Framework\TestCase;
use Process\Metadata\ProcessMetadata;
use Process\SchemaMetadataBuilder;
use Process\Test\ProcessConfigurationDataProvider;

class ProcessMetadataTest extends TestCase
{
    /**
     * @var ProcessMetadata
     */
    private $processMetadata;

    protected function setUp()
    {
        parent::setUp();
        $configuration = ProcessConfigurationDataProvider::getBranchingProcessConfiguration();
        $metadataBuilder = new SchemaMetadataBuilder();
        $this->processMetadata = $metadataBuilder->buildFromArray($configuration);
    }

    /**
     * @see ProcessMetadata::getProcessName()
     * @see ProcessMetadata::getInitialActivity()
     * @see ProcessMetadata::getActivities()
     * @see ProcessMetadata::getFlows()
     * @see ProcessMetadata::getActivityData()
     */
    public function testAllMethods()
    {
        $this->assertEquals('Test', $this->processMetadata->getProcessName());
        $this->assertEquals('item1', $this->processMetadata->getInitialActivity());
        $this->assertEquals(7, count($this->processMetadata->getActivities()));
        $this->assertEquals(6, count($this->processMetadata->getFlows()));

        $item1Data = $this->processMetadata->getActivityData('item1');
        $this->assertSame([
            'activityId' => 'item1Handler',
            'meta' => [],
            'nextActivity' => 'item2',
        ], $item1Data);
        $item3Data = $this->processMetadata->getActivityData('item3');
        $this->assertSame([
            'activityId' => 'item3Handler',
            'meta' => [],
        ], $item3Data);
    }
}