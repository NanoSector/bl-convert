<?php

namespace Yoshi2889\BlConvert\Tests;

use PHPUnit\Framework\TestCase;
use Yoshi2889\BlConvert\Utilities\HostnameValidator;

class HostnameValidatorTest extends TestCase
{
    public function testValidateOne()
    {
        $this->assertTrue(HostnameValidator::validate('test.com'));
        $this->assertTrue(HostnameValidator::validate('subdomain.test.com'));
        $this->assertFalse(HostnameValidator::validate('subdomain.@test.com'));
        $this->assertFalse(HostnameValidator::validate('subdomain._test.com'));
        $this->assertFalse(HostnameValidator::validate('@test.com'));
        $this->assertFalse(HostnameValidator::validate('_test.com'));
        $this->assertFalse(HostnameValidator::validate('test ing.com'));
    }
}
