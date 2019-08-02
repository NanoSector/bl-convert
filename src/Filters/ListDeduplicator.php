<?php


namespace Yoshi2889\BlConvert\Filters;


use Yoshi2889\BlConvert\ListTypes\HostnameList;
use Yoshi2889\BlConvert\ListTypes\ListInterface;

/**
 * Class ListDeduplicator
 *
 * This class removes duplicates from a given list.
 * @package Yoshi2889\BlConvert\Filters
 */
class ListDeduplicator implements ListFilter
{
    /**
     * @inheritDoc
     */
    public function filter(ListInterface $list): HostnameList
    {
        $hostnames = [];

        foreach ($list->getHostnames() as $hostname) {
            if (!in_array($hostname, $hostnames, true)) {
                $hostnames[] = $hostname;
            }
        }

        return new HostnameList($hostnames);
    }
}