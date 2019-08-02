<?php

namespace Yoshi2889\BlConvert\Tests;

use PHPUnit\Framework\TestCase;
use Yoshi2889\BlConvert\Exceptions\InvalidFileFormatException;
use Yoshi2889\BlConvert\Exceptions\UnreadableFileException;
use Yoshi2889\BlConvert\InputStrategies\WildCardListInput;

class WildCardListInputTest extends TestCase
{

    public function testFromFile()
    {
        $expected = [
            'test.com',
            'testing.com',
            'foo.bar'
        ];

        $output = WildCardListInput::fromFile(__DIR__ . '/testInputFiles/wildcardinput.txt');
        $this->assertEquals($expected, $output->getHostnames());

        $outputComments = WildCardListInput::fromFile(__DIR__ . '/testInputFiles/wildcardinput_comments.txt');
        $this->assertEquals($expected, $output->getHostnames());
    }

    public function testFromInvalidFile()
    {
        $this->expectException(InvalidFileFormatException::class);
        WildCardListInput::fromFile(__DIR__ . '/testInputFiles/wildcardinput_invalid.txt');
    }

    public function testFromMissingFile()
    {
        $this->expectException(UnreadableFileException::class);
        WildCardListInput::fromFile(__DIR__ . '/testInputFiles/wildcardinput_missing.txt');
    }

    public function testFromDomains()
    {
        $domains = [
            'test.com',
            'testing.com'
        ];

        $output = WildCardListInput::fromHostnames($domains);

        $this->assertEquals($domains, $output->getHostnames());
    }
}
