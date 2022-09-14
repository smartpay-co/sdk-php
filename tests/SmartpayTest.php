<?php

namespace Tests\Smartpay;

use Tests\TestCase;

use Smartpay\Smartpay;

final class SmartpayTest extends TestCase
{
    public function testApiUrlAccessors()
    {
        Smartpay::setApiUrl(Null);
        static::assertSame('https://api.smartpay.co/v1', Smartpay::getApiUrl());

        Smartpay::setApiUrl('https://api.smartpay.co/v2');
        static::assertSame('https://api.smartpay.co/v2', Smartpay::getApiUrl());
    }

    public function testCheckoutUrlAccessors()
    {
        static::assertSame('https://checkout.smartpay.co', Smartpay::getCheckoutUrl());

        Smartpay::setCheckoutUrl(Null);
        static::assertSame('https://checkout.smartpay.co', Smartpay::getCheckoutUrl());

        Smartpay::setCheckoutUrl('https://checkout.smartpay.co/v1');
        static::assertSame('https://checkout.smartpay.co/v1', Smartpay::getCheckoutUrl());
    }

    public function testPostTimeoutAccessors()
    {
        static::assertSame(30, Smartpay::getPostTimeout());

        Smartpay::setPostTimeout(Null);
        static::assertSame(30, Smartpay::getPostTimeout());

        Smartpay::setPostTimeout(100);
        static::assertSame(100, Smartpay::getPostTimeout());
    }

    public function testPublicKeyAccessors()
    {
        Smartpay::setPublicKey(Null);
        static::assertNull(Smartpay::getPublicKey());

        Smartpay::setPublicKey('pk_test_123');
        static::assertSame('pk_test_123', Smartpay::getPublicKey());
    }

    public function testSecretKeyAccessors()
    {
        Smartpay::setSecretKey(Null);
        static::assertNull(Smartpay::getSecretKey());

        Smartpay::setSecretKey('sk_test_123');
        static::assertSame('sk_test_123', Smartpay::getSecretKey());
    }
}
