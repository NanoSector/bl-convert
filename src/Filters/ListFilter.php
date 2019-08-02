<?php


namespace Yoshi2889\BlConvert\Filters;


use Yoshi2889\BlConvert\Exceptions\InvalidHostnameException;
use Yoshi2889\BlConvert\ListTypes\HostnameList;
use Yoshi2889\BlConvert\ListTypes\ListInterface;

interface ListFilter
{
    /**
     * Filters a list based on the algorithm defined in the class.
     * @param ListInterface $list the list to filter
     * @return HostnameList a list with filtered hostnames
     * @throws InvalidHostnameException when the filtered set contains an invalid hostname
     */
    public function filter(ListInterface $list): HostnameList;
}