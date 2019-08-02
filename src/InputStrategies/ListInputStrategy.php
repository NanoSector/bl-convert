<?php

namespace Yoshi2889\BlConvert\InputStrategies;

use Yoshi2889\BlConvert\Exceptions\InvalidFileFormatException;
use Yoshi2889\BlConvert\Exceptions\UnreadableFileException;
use Yoshi2889\BlConvert\ListTypes\HostnameList;

interface ListInputStrategy
{
    /**
     * Takes a file and turns it into a list instance.
     * @param string $filename the filename to read
     * @return HostnameList an instance of the appropriate list type
     * @throws UnreadableFileException when the file could not be read
     * @throws InvalidFileFormatException when the file is in an invalid format
     */
    public static function fromFile(string $filename): HostnameList;

    /**
     * Takes an array of hostnames and turns it into a list instance.
     * @param array $hostnames the hostnames to add to the list instance
     * @return HostnameList an instance of the appropriate list type
     */
    public static function fromHostnames(array $hostnames): HostnameList;
}