<?php

namespace Process\Test\Unit;

use PHPUnit\Framework\TestCase;
use Process\ActivityVisitor\ActivityVisitorInterface;
use Process\ActivityVisitor\NameConvertableActivityVisitor;
use Process\Context\ExecutionContext;
use Process\Contract\ActivityNameConverterInterface;

class NameConvertableActivityVisitorTest extends TestCase
{
    /**
     * @see NameConvertableActivityVisitor::visitActivity()
     */
    public function testVisitActivity()
    {
        $activityNameConverter = $this->createMock(ActivityNameConverterInterface::class);
        $activityNameConverter
            ->expects($this->once())
            ->method('convertNameActivity')
        ;
        $activityVisitor = $this->createMock(ActivityVisitorInterface::class);
        $activityVisitor
            ->expects($this->once())
            ->method('visitActivity')
        ;
        $nameConvertableVisitor = new NameConvertableActivityVisitor($activityVisitor, $activityNameConverter);
        $execContext = $this->getMockBuilder(ExecutionContext::class)->disableOriginalConstructor()->getMock();
        $nameConvertableVisitor->visitActivity($execContext, 'handler');
    }
}