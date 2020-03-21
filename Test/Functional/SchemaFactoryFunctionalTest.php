<?php

namespace Process\Test\Functional;

use Process\SchemaItem;
use Process\Test\ProcessBaseTest;
use Process\Test\ProcessConfigurationDataProvider;

class SchemaFactoryFunctionalTest extends ProcessBaseTest
{
    /**
     * Testing the mechanism for creating a process diagram from metadata
     *
     * @param array $configuration
     * @param string $type
     * @dataProvider configurationForCreateProcess
     */
    public function testCreateProcess(array $configuration, string $type)
    {
        $schema = $this->createSchema($configuration);
        switch ($type) {
            case 'linear' :
                $this->assertForLinearSchema($schema);
                break;
            case 'branching' :
                $this->assertForBranchingSchema($schema);
        }
    }

    /**
     * @return array
     */
    public function configurationForCreateProcess() : array
    {
        return [
            [
                ProcessConfigurationDataProvider::getLinearProcessConfiguration(),
                'linear',
            ],
            [
                ProcessConfigurationDataProvider::getBranchingProcessConfiguration(),
                'branching',
            ],
        ];
    }

    /**
     * @param SchemaItem $schemaItem
     */
    private function assertForLinearSchema(SchemaItem $schemaItem)
    {
        $this->assertEquals('item1', $schemaItem->getName());

        $this->assertForSchemaItem($schemaItem, 'item2', 0);
        $nextItem2 = $this->getProperty($schemaItem, 'nextItem');

        $this->assertForSchemaItem($nextItem2, 'item3', 0);
        $nextItem3 = $this->getProperty($nextItem2, 'nextItem');
        $this->assertThatEndSchemaItem($nextItem3);
    }

    /**
     * @param SchemaItem $schemaItem
     */
    private function assertForBranchingSchema(SchemaItem $schemaItem)
    {
        // linear part
        $this->assertEquals('item1', $schemaItem->getName());

        $this->assertForSchemaItem($schemaItem, 'item2', 0);
        $nextItem2 = $this->getProperty($schemaItem, 'nextItem');

        $this->assertForSchemaItem($nextItem2, 'item3', 2);
        $nextItem3 = $this->getProperty($nextItem2, 'nextItem');

        $this->assertThatEndSchemaItem($nextItem3);

        $alternativeNextItems2 = $this->getProperty($nextItem2, 'alternativeNextItems');
        //branching
        $firstBranchTested = false;
        $secondBranchTested = false;
        /** @var SchemaItem $schemaItemFromBranch */
        foreach ($alternativeNextItems2 as $schemaItemFromBranch) {
            if($schemaItemFromBranch->getName() == 'item4') {
                $this->assertForSchemaItem($schemaItemFromBranch, 'item5', 0);
                $nextItem5 = $this->getProperty($schemaItemFromBranch, 'nextItem');
                $this->assertThatEndSchemaItem($nextItem5);
                $firstBranchTested = true;
            }
            if($schemaItemFromBranch->getName() == 'item6') {
                $this->assertForSchemaItem($schemaItemFromBranch, 'item7', 0);
                $nextItem7 = $this->getProperty($schemaItemFromBranch, 'nextItem');
                $this->assertThatEndSchemaItem($nextItem7);
                $secondBranchTested = true;
            }
        }
        $this->assertTrue($firstBranchTested);
        $this->assertTrue($secondBranchTested);
    }

    /**
     * @param SchemaItem $schemaItem
     * @param $nextItemName
     * @param $countAlternativeNextItems
     */
    private function assertForSchemaItem(SchemaItem $schemaItem, $nextItemName, $countAlternativeNextItems)
    {
        $nextItem = $this->getProperty($schemaItem, 'nextItem');
        $this->assertEquals($nextItemName, $nextItem->getName());

        $alternativeNextItem = $this->getProperty($schemaItem, 'alternativeNextItems');
        $this->assertEquals($countAlternativeNextItems, count($alternativeNextItem));
    }

    /**
     * @param SchemaItem $schemaItem
     */
    private function assertThatEndSchemaItem(SchemaItem $schemaItem)
    {
        $nextItem = $this->getProperty($schemaItem, 'nextItem');
        $this->assertNull($nextItem);

        $alternativeNextItem = $this->getProperty($schemaItem, 'alternativeNextItems');
        $this->assertEquals(0, count($alternativeNextItem));
    }

    /**
     * @param SchemaItem $schemaItem
     * @param $propertyName
     * @return mixed
     */
    private function getProperty(SchemaItem $schemaItem, $propertyName)
    {
        $schemaRefl = new \ReflectionClass($schemaItem);
        $prop = $schemaRefl->getProperty($propertyName);
        $prop->setAccessible(true);
        $value = $prop->getValue($schemaItem);
        $prop->setAccessible(false);
        return $value;
    }
}