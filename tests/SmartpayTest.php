<?php

namespace Tests\Smartpay;

use Tests\TestCase;

use Smartpay\Smartpay;

final class SmartpayTest extends TestCase
{
    public function set_up()
    {
        putenv('SMARTPAY_PUBLIC_KEY');
        putenv('SMARTPAY_SECRET_KEY');
        putenv('SMARTPAY_API_PREFIX');
        parent::set_up();
    }

    public function testConstructorWithoutArguments()
    {
        $smartpay = new Smartpay();
        $this->assertEquals('', $smartpay->getPublicKey());
        $this->assertEquals('', $smartpay->getSecretKey());
        $this->assertEquals(Smartpay::DEFAULT_API_URL, $smartpay->getApiUrl());
    }

    public function testConstructorWithEnv()
    {
        putenv('SMARTPAY_PUBLIC_KEY=public_key');
        putenv('SMARTPAY_SECRET_KEY=secret_key');
        putenv('SMARTPAY_API_PREFIX=api_url');
        $smartpay = new Smartpay();
        $this->assertEquals('public_key', $smartpay->getPublicKey());
        $this->assertEquals('secret_key', $smartpay->getSecretKey());
        $this->assertEquals('api_url', $smartpay->getApiUrl());
    }

    public function testConstructor()
    {
        $smartpay = new Smartpay('sec', 'pub');
        $this->assertEquals('pub', $smartpay->getPublicKey());
        $this->assertEquals('sec', $smartpay->getSecretKey());
        $this->assertEquals(Smartpay::DEFAULT_API_URL, $smartpay->getApiUrl());
    }
}
