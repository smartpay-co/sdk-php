<?php

namespace Tests;

use Smartpay\Smartpay;

abstract class TestCase extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
    public function set_up()
    {
        Smartpay::reset();

        $SMARTPAY_API_PREFIX = getenv('SMARTPAY_API_PREFIX');
        $SMARTPAY_CHECKOUT_URL = getenv('SMARTPAY_CHECKOUT_URL');

        if ($SMARTPAY_API_PREFIX) {
            Smartpay::setApiUrl($SMARTPAY_API_PREFIX);
        }

        if ($SMARTPAY_CHECKOUT_URL) {
            Smartpay::setCheckoutUrl($SMARTPAY_CHECKOUT_URL);
        }
    }

    public function tear_down()
    {
        Smartpay::reset();
    }
}
