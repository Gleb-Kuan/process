<?php

namespace Process\Test\Functional;

class BranchingProcessTest extends TestProcessBase
{
    public function testMainBranch()
    {
        $activity = $this->prepareActivityForLeftBranch(TestedActivity::MAIN_BRANCH);
        $execContext = $this->prepareExecutionContext($activity);
        $crawler = $this->prepareSchemaCrawler();

        $crawler->start($execContext);
        $this->assertSame(['item1', 'item2', 'item3'], $crawler->getVisitedNodes());
    }

    public function testLeftBranch()
    {
        $activity = $this->prepareActivityForLeftBranch(TestedActivity::LEFT_BRANCH);
        $execContext = $this->prepareExecutionContext($activity);
        $crawler = $this->prepareSchemaCrawler();

        $crawler->start($execContext);
        $this->assertSame(['item1', 'item2', 'item4', 'item5'], $crawler->getVisitedNodes());
    }

    public function testRightBranch()
    {
        $activity = $this->prepareActivityForLeftBranch(TestedActivity::RIGHT_BRANCH);
        $execContext = $this->prepareExecutionContext($activity);
        $crawler = $this->prepareSchemaCrawler();

        $crawler->start($execContext);
        $this->assertSame(['item1', 'item2', 'item6', 'item7'], $crawler->getVisitedNodes());
    }

    private function prepareActivityForLeftBranch(string $branch)
    {
        return new TestedActivity($branch, TestedActivity::BRANCHING_PROCESS);
    }
}