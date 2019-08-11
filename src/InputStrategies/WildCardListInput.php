<?php


namespace Yoshi2889\BlConvert\InputStrategies;

use Yoshi2889\BlConvert\Exceptions\InvalidHostnameException;
use Yoshi2889\BlConvert\Exceptions\InvalidFileFormatException;
use Yoshi2889\BlConvert\Exceptions\UnreadableFileException;
use Yoshi2889\BlConvert\ListTypes\HostnameList;

class WildCardListInput implements ListInputStrategy
{
    /** @inheritDoc
     * @throws InvalidHostnameException when a hostname in the file is invalid
     */
    public static function fromFile(string $filename): HostnameList
    {
        $resource = @fopen($filename, 'rb');
        if (!$resource) {
            throw new UnreadableFileException('Unable to read the given file: ' . $filename);
        }

        $hostnames = [];
        $lineNum = 0;
        while (($line = fgets($resource)) !== false) {
            $line = trim($line);
            $lineNum++;

            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            if (count(explode(' ', $line)) > 1) {
                throw new InvalidFileFormatException('File contains data after hostname on line ' . $lineNum  . '; is it a valid wildcard list file? Full line: ' . $line);
            }

            $hostnames[] = $line;
        }

        return new HostnameList($hostnames);
    }
}