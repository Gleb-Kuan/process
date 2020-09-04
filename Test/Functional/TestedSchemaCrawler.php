<?php

namespace Process\Test\Functional;

use Process\SchemaCrawler;

class TestedSchemaCrawler extends SchemaCrawler
{
    private $visitedNodes = [];

    protected function onNode(string $itemName)
    {
        $this->visitedNodes[] = $itemName;
        parent::onNode($itemName);
    }

    public function getVisitedNodes(): array
    {
        return $this->visitedNodes;
    }
}