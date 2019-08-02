<?php

namespace Yoshi2889\BlConvert\Filters;

use Yoshi2889\BlConvert\Exceptions\FilterDepthException;
use Yoshi2889\BlConvert\ListTypes\HostnameList;
use Yoshi2889\BlConvert\ListTypes\ListInterface;

/**
 * Class ListGeneralizer
 *
 * This class generalizes lists to a certain depth, e.g. removes subdomains from the list.
 * @package Yoshi2889\BlConvert
 */
class ListGeneralizer implements ListFilter
{
    /**
     * The filter depth
     *
     * For example:
     * depth: 1 -> this.is.an.example -> example
     * depth: 2 -> this.is.an.example -> an.example
     * depth: 3 -> this.is.an.example -> is.an.example
     * etc.
     *
     * @var int
     */
    private $depth;

    /**
     * ListGeneralizer constructor.
     * @param int $depth the depth to generalize to.
     *
     * @throws FilterDepthException when the set depth is invalid
     * @see ListGeneralizer::$depth
     */
    public function __construct(int $depth = 3)
    {
        if ($depth < 2) {
            throw new FilterDepthException('Filter depth cannot be lower than 2');
        }
        $this->depth = $depth;
    }

    /**
     * @inheritDoc
     */
    public function filter(ListInterface $list): HostnameList
    {
        $hostnames = [];

        foreach ($list->getHostnames() as $hostname) {
            $hostnames[] = $this->generalizeHostname($hostname);
        }

        return new HostnameList($hostnames);
    }

    /**
     * Generalizes a hostname according to the set depth
     *
     * @param string $hostname the hostname to generalize
     * @return string the generalized hostname
     * @see ListGeneralizer::$depth
     */
    private function generalizeHostname(string $hostname): string
    {
        $pieces = explode('.', $hostname);

        if (count($pieces) === $this->depth) {
            return $hostname;
        }

        // TODO: find a more elegant solution
        return implode('.', array_reverse(array_slice(array_reverse($pieces), 0, $this->depth)));
    }
}