<?php

namespace Yoshi2889\BlConvert\Tests;

use Yoshi2889\BlConvert\Exceptions\FilterDepthException;
use Yoshi2889\BlConvert\Filters\ListGeneralizer;
use PHPUnit\Framework\TestCase;
use Yoshi2889\BlConvert\ListTypes\HostnameList;

class ListGeneralizerTest extends TestCase
{
    public function testFilter()
    {
        $hostnames = ['foo.bar.baz', 'this.is.a.test'];
        $expectedTwo = ['bar.baz', 'a.test'];
        $expectedThree = ['foo.bar.baz', 'is.a.test'];
        $expectedFour = ['foo.bar.baz', 'this.is.a.test'];

        $list = new HostnameList($hostnames);

        $filterTwo = new ListGeneralizer(2);
        $filterThree = new ListGeneralizer(3);
        $filterFour = new ListGeneralizer(4);

        $filteredTwo = $filterTwo->filter($list);
        $filteredThree = $filterThree->filter($list);
        $filteredFour = $filterFour->filter($list);

        $this->assertEquals($expectedTwo, $filteredTwo->getHostnames());
        $this->assertEquals($expectedThree, $filteredThree->getHostnames());
        $this->assertEquals($expectedFour, $filteredFour->getHostnames());
    }

    public function testCreateFilterInvalidParameters()
    {
        $this->expectException(FilterDepthException::class);
        new ListGeneralizer(1);
    }
}
