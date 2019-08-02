<?php


namespace Yoshi2889\BlConvert\ListTypes;


use Yoshi2889\BlConvert\Exceptions\InvalidHostnameException;
use Yoshi2889\BlConvert\Utilities\HostnameValidator;

class HostnameList implements ListInterface
{
    private $hostnames;

    /**
     * WildCardList constructor.
     * @param array $hostnames the hostnames to place in this list
     * @throws InvalidHostnameException
     */
    public function __construct(array $hostnames = [])
    {
        if (!HostnameValidator::validateAll($hostnames)) {
            throw new InvalidHostnameException('WildCardList constructor got one or more invalid hostnames');
        }
        $this->hostnames = $hostnames;
    }

    /** @inheritDoc */
    public function getHostnames(): array
    {
        return $this->hostnames;
    }

    /**
     * Splits the current list into sublists each containing at most $every items.
     * @param int $every the size at which to split the current list.
     * Please note that the default (200.000) is sensible only for Little Snitch lists; be sure to change this for other types
     *
     * @return HostnameList[] an array of hostname lists
     * @throws InvalidHostnameException this should never happen, if it does, report a bug
     */
    public function split(int $every = 200000): array
    {
        $hostnameCount = count($this->getHostnames());

        // avoid a potentially memory hungry routine
        if ($hostnameCount <= $every) {
            return [$this];
        }

        $listCount = ceil($hostnameCount / $every);

        $lists = [];
        for ($i = 0; $i < $listCount; $i++) {
            $hostnames = array_slice($this->getHostnames(), $i * $every, $every);
            $lists[] = new HostnameList($hostnames);
        }

        return $lists;
    }
}