<?php


namespace Yoshi2889\BlConvert\OutputStrategies;


use Yoshi2889\BlConvert\Exceptions\ListSizeException;
use Yoshi2889\BlConvert\ListTypes\ListInterface;

class LittleSnitchListOutput implements ListOutputStrategy
{
    /**
     * The amount of items to store per file.
     * By default this is 200.000 for Little Snitch.
     * @var int
     */
    public const LITTLE_SNITCH_MAX_RULES_PER_FILE = 200000;

    /**
     * The file to write to.
     * @var string
     */
    private $filename;

    /**
     * Uses the blocklist format introduced with Little Snitch 4.2. Greatly saves on file size.
     * @var bool
     */
    private $useBlocklistFormat;

    /**
     * A descriptive name for the list, shown to the end user
     * @var string
     */
    private $listName;

    /**
     * LittleSnitchListOutput constructor.
     * @param string $filename the filename to store the list in
     * @param string $listName a descriptive name for the list, shown to the end user
     * @param bool $useBlocklistFormat
     */
    public function __construct(string $filename, string $listName, bool $useBlocklistFormat = true)
    {
        $this->filename = $filename;
        $this->useBlocklistFormat = $useBlocklistFormat;
        $this->listName = $listName;
    }

    /**
     * @inheritDoc
     * @throws ListSizeException when the list is too large for Little Snitch to handle
     */
    public function writeList(ListInterface $list): void
    {
        if (count($list->getHostnames()) > self::LITTLE_SNITCH_MAX_RULES_PER_FILE) {
            throw new ListSizeException('The size of this list exceeds the maximum amount of rules per file. Little Snitch will not import this and thus I will not write it. Period.');
        }

        if ($this->useBlocklistFormat) {
            $this->writeBlocklistFile($list->getHostnames());
        } else {
            $this->writeRulesFile($list->getHostnames());
        }
    }

    private function writeBlocklistFile(array $hostnames): void
    {
        $json = [
            'name' => $this->listName,
            'description' => 'Generated by https://github.com/Yoshi2889/bl-convert',
            'denied-remote-hosts' => $hostnames
        ];

        file_put_contents($this->filename, json_encode($json));
    }

    private function writeRulesFile(array $hostnames): void
    {
        $rules = [];

        foreach ($hostnames as $hostname) {
            $rules[] = [
                'action' => 'deny',
                'process' => 'any',
                'remote-hosts' => $hostname
            ];
        }

        $json = [
            'name' => $this->listName,
            'description' => 'Generated by https://github.com/Yoshi2889/bl-convert',
            'rules' => $rules
        ];

        file_put_contents($this->filename, json_encode($json));
    }
}