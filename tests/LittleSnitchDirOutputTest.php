<?php

namespace Yoshi2889\BlConvert\Tests;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Yoshi2889\BlConvert\Exceptions\InvalidDestinationException;
use Yoshi2889\BlConvert\ListTypes\HostnameList;
use Yoshi2889\BlConvert\OutputStrategies\LittleSnitchDirOutput;
use PHPUnit\Framework\TestCase;

class LittleSnitchDirOutputTest extends TestCase
{
    public function testWriteListCleanDirectory()
    {
        mkdir(__DIR__ . '/cleanDirectory');
        mkdir(__DIR__ . '/cleanDirectory/test');
        touch(__DIR__ . '/cleanDirectory/testfile.txt');

        $strategy = new LittleSnitchDirOutput(__DIR__ . '/cleanDirectory', 'Test list');

        $list = new HostnameList(['test.com']);

        $strategy->writeList($list);

        $this->assertFileNotExists(__DIR__ . '/cleanDirectory/testfile.txt');
        $this->assertFileNotExists(__DIR__ . '/cleanDirectory/test');
        $this->assertFileExists(__DIR__ . '/cleanDirectory/littlesnitch_1.lsrules');

        $strategy = new LittleSnitchDirOutput(__DIR__ . '/cleanDirectory', 'Test list', 'littlesnitch2_', false);

        $strategy->writeList($list);

        $this->assertFileExists(__DIR__ . '/cleanDirectory/littlesnitch_1.lsrules');
        $this->assertFileExists(__DIR__ . '/cleanDirectory/littlesnitch2_1.lsrules');
    }

    public function testWriteListDirectoryFile()
    {
        touch(__DIR__ . '/cleanDirectory');

        $this->expectException(InvalidDestinationException::class);
        new LittleSnitchDirOutput(__DIR__ . '/cleanDirectory', 'Test list');
    }

    // slow one!
    public function testWriteLotsOfRules()
    {
        mkdir(__DIR__ . '/cleanDirectory');

        $items = explode(' ', trim(str_repeat('test.com ', 200010)));
        $list = new HostnameList($items);

        $strategy = new LittleSnitchDirOutput(__DIR__ . '/cleanDirectory', 'Test list');
        $strategy->writeList($list);
        $this->assertFileExists(__DIR__ . '/cleanDirectory/littlesnitch_1.lsrules');
        $this->assertFileExists(__DIR__ . '/cleanDirectory/littlesnitch_2.lsrules');
    }

    public function tearDown()
    {
        if (!file_exists(__DIR__ . '/cleanDirectory')) {
            return;
        }

        if (is_file(__DIR__ . '/cleanDirectory')) {
            unlink(__DIR__ . '/cleanDirectory');
            return;
        }

        $directoryIterator = new RecursiveDirectoryIterator(__DIR__ . '/cleanDirectory', RecursiveDirectoryIterator::SKIP_DOTS);
        $contents = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach($contents as $item) {
            if ($item->isDir()){
                rmdir($item->getRealPath());
            } else {
                unlink($item->getRealPath());
            }
        }
        rmdir(__DIR__ . '/cleanDirectory');
    }
}
