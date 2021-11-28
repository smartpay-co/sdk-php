<?php

namespace Tests;

use Smartpay\Smartpay;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        Smartpay::reset();
    }

    public function tearDown()
    {
        Smartpay::reset();
    }
}
