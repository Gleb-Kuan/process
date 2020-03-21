<?php

namespace Process\Test;

use Process\Exception\LogicalException;
use Process\Exception\ProcessBuildException;
use Process\ProcessMetadataBuilder;

/**
 * Class ProcessMetadataBuilderTest
 */
class ProcessMetadataBuilderTest extends ProcessBaseTest
{
    /**
     * @param ProcessMetadataBuilder $processMetadataBuilder
     * @dataProvider prepareProcessMetadataBuilder
     */
    public function testAddFlowWhenLogicException(ProcessMetadataBuilder $processMetadataBuilder)
    {
        $this->expectException(LogicalException::class);
        $processMetadataBuilder->addFlow('item1', 'item1');
    }

    /**
     * @param ProcessMetadataBuilder $processMetadataBuilder
     * @dataProvider prepareProcessMetadataBuilder
     */
    public function testAddFlowWhenActivityFromMissing(ProcessMetadataBuilder $processMetadataBuilder)
    {
        $this->expectException(ProcessBuildException::class);
        $this->expectExceptionCode(ProcessBuildException::ACTIVITY_MISSING_CODE);
        $processMetadataBuilder->addFlow('item0', 'item1');
    }

    /**
     * @param ProcessMetadataBuilder $processMetadataBuilder
     * @dataProvider prepareProcessMetadataBuilder
     */
    public function testAddFlowWhenActivityToMissing(ProcessMetadataBuilder $processMetadataBuilder)
    {
        $this->expectException(ProcessBuildException::class);
        $this->expectExceptionCode(ProcessBuildException::ACTIVITY_MISSING_CODE);
        $processMetadataBuilder->addFlow('item3', 'item4');
    }

    /**
     * Testing exception while set initial activity
     *
     * @param ProcessMetadataBuilder $processMetadataBuilder
     * @dataProvider prepareProcessMetadataBuilder
     */
    public function testSetInitialActivity(ProcessMetadataBuilder $processMetadataBuilder)
    {
        $this->expectException(ProcessBuildException::class);
        $this->expectExceptionCode(ProcessBuildException::ACTIVITY_MISSING_CODE);
        $processMetadataBuilder->setInitialActivity('item4');
    }

    /**
     * Testing exception while create process metadata
     *
     * @param ProcessMetadataBuilder $processMetadataBuilder
     * @dataProvider prepareProcessMetadataBuilder
     */
    public function testGetProcessMetadata(ProcessMetadataBuilder $processMetadataBuilder)
    {
        $this->expectException(ProcessBuildException::class);
        $this->expectExceptionCode(ProcessBuildException::INITIAL_ACTIVITY_MISSING_CODE);
        $processMetadataBuilder->getProcessMetadata();
    }

    /**
     * @return array
     */
    public function prepareProcessMetadataBuilder() : array
    {
        $processMetadataBuilder = new ProcessMetadataBuilder();
        $processMetadataBuilder->setProcessName('Test');
        $processMetadataBuilder->addSchemaItem('item1', 'item1Handler');
        $processMetadataBuilder->addSchemaItem('item2', 'item2Handler');
        $processMetadataBuilder->addSchemaItem('item3', 'item3Handler');
        return [
            [
                $processMetadataBuilder
            ]
        ];
    }
}