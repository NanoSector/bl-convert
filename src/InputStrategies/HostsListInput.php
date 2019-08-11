<?php


namespace Yoshi2889\BlConvert\InputStrategies;

use Yoshi2889\BlConvert\Exceptions\InvalidFileFormatException;
use Yoshi2889\BlConvert\Exceptions\UnreadableFileException;
use Yoshi2889\BlConvert\ListTypes\HostnameList;

class HostsListInput implements ListInputStrategy
{
    /**
     * @inheritDoc
     */
    public static function fromFile(string $filename): HostnameList
    {
        $resource = @fopen($filename, 'rb');
        if ($resource === false) {
            throw new UnreadableFileException('Unable to read the given file: ' . $filename);
        }

        $hostnames = [];
        while (($line = fgets($resource)) !== false) {
            $line = trim($line);

            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            $pieces = explode(' ', $line);

            if (count($pieces) < 2) {
                throw new InvalidFileFormatException('The given hosts-based list contains an invalid rule: ' . $line);
            }

            // the first element is not a domain we should block
            array_shift($pieces);

            // this is less resource intensive than array_merge
            foreach ($pieces as $piece) {
                $hostnames[] = $piece;
            }
        }

        return new HostnameList($hostnames);
    }
}