<?php

namespace Yoshi2889\BlConvert\Tests;

use PHPUnit\Framework\TestCase;
use Yoshi2889\BlConvert\Exceptions\InvalidFileFormatException;
use Yoshi2889\BlConvert\Exceptions\UnreadableFileException;
use Yoshi2889\BlConvert\InputStrategies\HostsListInput;

class HostsListInputTest extends TestCase
{

    public function testFromFile()
    {
        $expected = [
            'test.com',
            'testing.com',
            'foo.bar',
            'bar.baz'
        ];

        $output = HostsListInput::fromFile(__DIR__ . '/testInputFiles/hostsinput.txt');
        $this->assertEquals($expected, $output->getHostnames());

        $outputComments = HostsListInput::fromFile(__DIR__ . '/testInputFiles/hostsinput_comments.txt');
        $this->assertEquals($expected, $outputComments->getHostnames());
    }

    public function testFromInvalidFile()
    {
        $this->expectException(InvalidFileFormatException::class);
        HostsListInput::fromFile(__DIR__ . '/testInputFiles/hostsinput_invalid.txt');
    }

    public function testFromMissingFile()
    {
        $this->expectException(UnreadableFileException::class);
        HostsListInput::fromFile(__DIR__ . '/testInputFiles/hostsinput_missing.txt');
    }
}
