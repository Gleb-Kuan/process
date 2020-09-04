<?php

namespace Process\Test\Functional;

class LinearProcessTest extends TestProcessBase
{
    public function test()
    {
        $activity = new TestedActivity(TestedActivity::MAIN_BRANCH, TestedActivity::LINEAR_PROCESS);
        $execContext = $this->prepareExecutionContext($activity);
        $crawler = $this->prepareSchemaCrawler();

        $crawler->start($execContext);
        $this->assertSame(['item1', 'item2', 'item3'], $crawler->getVisitedNodes());
    }
}