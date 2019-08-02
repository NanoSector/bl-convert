<?php

namespace Yoshi2889\BlConvert\Tests;

use Yoshi2889\BlConvert\Exceptions\InvalidHostnameException;
use Yoshi2889\BlConvert\ListTypes\HostnameList;
use PHPUnit\Framework\TestCase;

class HostnameListTest extends TestCase
{

    public function testGetDomains()
    {
        $domains = ['test.com', 'testing.com', 'bar.baz'];
        $list = new HostnameList($domains);

        $this->assertEquals($domains, $list->getHostnames());
    }

    public function testConstructInvalidDomain()
    {
        new HostnameList(['test.com']);
        $this->expectException(InvalidHostnameException::class);
        new HostnameList(['@test.com']);
    }

    public function testSplit()
    {
        $domains = ['test.com', 'testing.com', 'bar.baz', 'foo.bar', 'this.is', 'a.long', 'list.yay'];

        $list = new HostnameList($domains);

        $lists = $list->split(3);

        $this->assertCount(3, $lists);
        $this->assertCount(3, $lists[0]->getHostnames());
        $this->assertCount(3, $lists[1]->getHostnames());
        $this->assertCount(1, $lists[2]->getHostnames());

        $lists = $list->split(10);

        $this->assertEquals([$list], $lists);
    }
}
