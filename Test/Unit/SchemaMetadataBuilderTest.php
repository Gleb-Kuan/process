<?php

namespace Process\Test\Unit;

use PHPUnit\Framework\TestCase;
use Process\Exception\LogicalException;
use Process\Exception\ProcessBuildException;
use Process\ProcessMetadataBuilder;
use Process\SchemaMetadataBuilder;

/**
 * Class ProcessMetadataBuilderTest
 */
class SchemaMetadataBuilderTest extends TestCase
{
    /**
     * @param ProcessMetadataBuilder $processMetadataBuilder
     * @dataProvider prepareSchemaMetadataBuilder
     */
    public function testAddFlowWhenLogicException(SchemaMetadataBuilder $processMetadataBuilder)
    {
        $this->expectException(LogicalException::class);
        $processMetadataBuilder->addFlow('item1', 'item1');
    }

    /**
     * @param ProcessMetadataBuilder $processMetadataBuilder
     * @dataProvider prepareSchemaMetadataBuilder
     */
    public function testAddFlowWhenActivityFromMissing(SchemaMetadataBuilder $processMetadataBuilder)
    {
        $this->expectException(ProcessBuildException::class);
        $this->expectExceptionCode(ProcessBuildException::ACTIVITY_MISSING_CODE);
        $processMetadataBuilder->addFlow('item0', 'item1');
    }

    /**
     * @param ProcessMetadataBuilder $processMetadataBuilder
     * @dataProvider prepareSchemaMetadataBuilder
     */
    public function testAddFlowWhenActivityToMissing(SchemaMetadataBuilder $processMetadataBuilder)
    {
        $this->expectException(ProcessBuildException::class);
        $this->expectExceptionCode(ProcessBuildException::ACTIVITY_MISSING_CODE);
        $processMetadataBuilder->addFlow('item3', 'item4');
    }

    /**
     * Testing exception while set initial activity
     *
     * @param ProcessMetadataBuilder $processMetadataBuilder
     * @dataProvider prepareSchemaMetadataBuilder
     */
    public function testSetInitialActivity(SchemaMetadataBuilder $processMetadataBuilder)
    {
        $this->expectException(ProcessBuildException::class);
        $this->expectExceptionCode(ProcessBuildException::ACTIVITY_MISSING_CODE);
        $processMetadataBuilder->setInitialActivity('item4');
    }

    /**
     * Testing exception while create process metadata
     *
     * @param ProcessMetadataBuilder $processMetadataBuilder
     * @dataProvider prepareSchemaMetadataBuilder
     */
    public function testGetProcessMetadata(SchemaMetadataBuilder $processMetadataBuilder)
    {
        $this->expectException(ProcessBuildException::class);
        $this->expectExceptionCode(ProcessBuildException::INITIAL_ACTIVITY_MISSING_CODE);
        $processMetadataBuilder->getProcessMetadata();
    }

    /**
     * @return array
     */
    public function prepareSchemaMetadataBuilder() : array
    {
        $processMetadataBuilder = new SchemaMetadataBuilder();
        $processMetadataBuilder->setProcessName('Test');
        $processMetadataBuilder->addSchemaItem('item1', 'item1Handler', []);
        $processMetadataBuilder->addSchemaItem('item2', 'item2Handler', []);
        $processMetadataBuilder->addSchemaItem('item3', 'item3Handler', []);
        return [
            [
                $processMetadataBuilder
            ]
        ];
    }
}