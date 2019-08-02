<?php


namespace Yoshi2889\BlConvert\Utilities;


class HostnameValidator
{
    /**
     * Validates that a given string is a hostname, e.g. that it is a valid URL
     * @param string $hostname the hostname to validate
     * @return bool whether the hostname is successfully validated
     */
    public static function validate(string $hostname): bool
    {
        return filter_var($hostname, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false;
    }

    /**
     * Validates that the contents of a given array consists of valid hostnames
     * @param array $hostnames the list of hostnames to validate
     * @return bool whether all hostnames in the array are valid
     */
    public static function validateAll(array $hostnames): bool
    {
        foreach ($hostnames as $hostname) {
            if (!self::validate($hostname)) {
                return false;
            }
        }

        return true;
    }

}