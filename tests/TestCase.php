<?php

namespace Tests;

use Smartpay\Smartpay;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        Smartpay::reset();
    }

    public function tearDown(): void
    {
        Smartpay::reset();
    }
}
