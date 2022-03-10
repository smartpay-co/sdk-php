<?php

namespace Tests\Responses;

use Tests\TestCase;
use GuzzleHttp\Psr7\Response;
use Smartpay\Smartpay;
use Smartpay\Responses\CheckoutSession;

final class CheckoutSessionTest extends TestCase
{
    public function testAsJson()
    {
        $response = new CheckoutSession(new Response(200, ['X-Foo' => 'Bar'], '{}'));
        $this->assertSame([], $response->asJson());
    }

    public function testRedirectUrl1()
    {
        Smartpay::setPublicKey('pk_test_1234');
        $response = new CheckoutSession(
            new Response(200, ['X-Foo' => 'Bar'], '{"id": "checkout_test_oTQpCvZzZ52UvKbrN5i4B8", "url": "https://checkout.smartpay.co/checkout_test_oTQpCvZzZ52UvKbrN5i4B8"}')
        );

        $this->assertTrue(
            stripos($response->redirectUrl(), 'checkout_test_oTQpCvZzZ52UvKbrN5i4B8') > 0
        );
    }

    public function testRedirectUrl2()
    {
        Smartpay::setPublicKey('pk_test_1234');
        $response = new CheckoutSession(
            new Response(200, ['X-Foo' => 'Bar'], '{"id": "checkout_test_oTQpCvZzZ52UvKbrN5i4B8", "url": "https://checkout.smartpay.co/checkout_test_oTQpCvZzZ52UvKbrN5i4B8"}')
        );

        $this->assertTrue(
            stripos($response->redirectUrl(['promotionCode' => 'ABCD']), 'ABCD') > 0
        );
    }
}
