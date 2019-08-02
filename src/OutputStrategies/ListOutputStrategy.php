<?php


namespace Yoshi2889\BlConvert\OutputStrategies;


use Yoshi2889\BlConvert\ListTypes\ListInterface;

interface ListOutputStrategy
{
    /**
     * Write a list to this output strategy.
     * @param ListInterface $list the list to write
     * @return void
     */
    public function writeList(ListInterface $list): void;
}