<?php

namespace Tests;

use Smartpay\Smartpay;

class TestCase extends \PHPUnit\Framework\TestCase
{
    const PUBLIC_KEY = 'pk_test_7smSiNAbAwsI2HKQE9e3hA';
    const SECRET_KEY = 'sk_test_KTGPODEMjGTJByn1pu8psb';

    public function setUp()
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

    public function tearDown()
    {
        Smartpay::reset();
    }


    public function testCreateCheckoutSession(): void
    {
        $api = new \Smartpay\Api(TestCase::PUBLIC_KEY, TestCase::SECRET_KEY);

        $data = $api->orders()->asJson();

        $this->assertSame(20, count($data['orders']));
    }
}
