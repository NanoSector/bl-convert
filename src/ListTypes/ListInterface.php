<?php


namespace Yoshi2889\BlConvert\ListTypes;

interface ListInterface
{
    /**
     * Converts the current list object into an array of hostnames.
     *
     * @return string[]
     */
    public function getHostnames(): array;
}