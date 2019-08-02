<?php


namespace Yoshi2889\BlConvert\OutputStrategies;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Yoshi2889\BlConvert\Exceptions\InvalidDestinationException;
use Yoshi2889\BlConvert\ListTypes\HostnameList;
use Yoshi2889\BlConvert\ListTypes\ListInterface;

class LittleSnitchDirOutput implements ListOutputStrategy
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var bool
     */
    private $clearDirectory;

    /**
     * The base filename to use for writing the files.
     * Files will be generated according to: $baseFilename$fileNum.lsrules. Example: littlesnitch_1.lsrules
     * @var string
     */
    private $baseFilename;

    /**
     * @var string
     */
    private $listName;

    /**
     * LittleSnitchDirOutput constructor.
     * @param string $directory the directory to store the list files in
     * @param string $listName a descriptive name for the list, shown to the end user
     * @param string $baseFilename the base filename to use for writing the files
     * @param bool $clearDirectory whether to clear the directory contents before writing list contents (recommended)
     * @throws InvalidDestinationException when the destination is invalid
     */
    public function __construct(string $directory, string $listName, string $baseFilename = 'littlesnitch_', bool $clearDirectory = true)
    {
        if (!is_writable($directory) || !is_dir($directory) || (!file_exists($directory) && !mkdir($directory) && !is_dir($directory))) {
            throw new InvalidDestinationException('The given directory is not a valid destination and could not be created');
        }
        $this->directory = $directory;
        $this->clearDirectory = $clearDirectory;
        $this->baseFilename = $baseFilename;
        $this->listName = $listName;
    }

    /** @inheritDoc */
    public function writeList(ListInterface $list): void
    {
        if ($this->clearDirectory) {
            $this->clearDirectory();
        }

        $list_buffer = [];
        $listnum = 1;
        $i = 0;
        foreach ($list->getHostnames() as $hostname) {
            $hostname = trim($hostname);

            if ($i === LittleSnitchListOutput::LITTLE_SNITCH_MAX_RULES_PER_FILE) {
                $lslo = new LittleSnitchListOutput(
                    $this->directory . '/' . $this->baseFilename . $listnum . '.lsrules',
                    $this->listName . ' (' . $listnum . ')'
                );
                $lslo->writeList(new HostnameList($list_buffer));
                $listnum++;
                $i = 0;
                $list_buffer = [];
            }

            $list_buffer[] = $hostname;
            $i++;
        }

        $lslo = new LittleSnitchListOutput(
            $this->directory . '/' . $this->baseFilename . $listnum . '.lsrules',
            $this->listName . ' (' . $listnum . ')'
        );
        $lslo->writeList(new HostnameList($list_buffer));
    }

    /**
     * Removes the contents of the set directory.
     */
    private function clearDirectory(): void
    {
        $directoryIterator = new RecursiveDirectoryIterator($this->directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $contents = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach($contents as $item) {
            if ($item->isDir()){
                rmdir($item->getRealPath());
            } else {
                unlink($item->getRealPath());
            }
        }
    }
}