<?php

namespace Yoshi2889\BlConvert\Tests;

use Yoshi2889\BlConvert\Filters\ListDeduplicator;
use PHPUnit\Framework\TestCase;
use Yoshi2889\BlConvert\ListTypes\HostnameList;

class ListDeduplicatorTest extends TestCase
{
    public function testFilter()
    {
        $hostnames = ['foo.bar', 'foo.bar', 'test.com'];
        $expected = ['foo.bar', 'test.com'];

        $list = new HostnameList($hostnames);

        $dedupFilter = new ListDeduplicator();

        $newList = $dedupFilter->filter($list);

        $this->assertEquals($expected, $newList->getHostnames());
    }
}
