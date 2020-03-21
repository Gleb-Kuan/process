<?php

namespace Process\Test;

use Process\Metadata\ProcessMetadata;
use Process\ProcessMetadataBuilder;
use Process\SchemaFactory;
use Process\SchemaItem;
use PHPUnit\Framework\TestCase;

class ProcessBaseTest extends TestCase
{
    /**
     * @param array $configuration
     * @return mixed
     */
    protected function createProcessMetadata(array $configuration) : ProcessMetadata
    {
        $processMetadataBuilder = new ProcessMetadataBuilder();
        return $processMetadataBuilder->buildFromArray($configuration);
    }

    /**
     * @param array $configuration
     * @return SchemaItem
     */
    protected function createSchema(array $configuration) : SchemaItem
    {
        $processMetadata = $this->createProcessMetadata($configuration);
        $processFactory = new SchemaFactory();
        return $processFactory->createSchema($processMetadata);
    }

    /**
     * @return array
     */
    protected function getExternalDependency()
    {
        return [
            'container' => new TestContainer(),
            'eventDispatcher' => new TestEventDispatcher(),
        ];
    }
}