<?php

namespace Tests;

use Tests\TestCase;

use Smartpay\Api;
use Smartpay\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class ApiTest extends TestCase
{
    public function testCheckoutSession()
    {
        $validPayload = [
            'successURL' => 'https://mock.test/success_url',
            'cancelURL' => 'https://mock.test/cancel_url',
        ];

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], '{}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(new GuzzleClient(['handler' => $handlerStack]));

        $api = new Api('pk_test_mock', 'sk_test_mock', $client);
        $this->assertSame([], $api->checkoutSession($validPayload)->asJson());
    }
}
