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

    public function testRedirectUrl()
    {
        Smartpay::setPublicKey('pk_test_1234');
        $response = new CheckoutSession(
            new Response(200, ['X-Foo' => 'Bar'], '{"id": "checkout_test_oTQpCvZzZ52UvKbrN5i4B8"}')
        );

        $this->assertSame(
            'https://checkout.smartpay.co/login?session-id=checkout_test_oTQpCvZzZ52UvKbrN5i4B8&public-key=pk_test_1234',
            $response->redirectUrl()
        );
    }
}
