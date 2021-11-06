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
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'Hello, World'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(new GuzzleClient(['handler' => $handlerStack]));

        $api = new Api('pk_test_mock', 'sk_test_mock', $client);
        $this->assertSame('Hello, World', $api->checkoutSession(['raw' => 'foo'])->asJson());
    }
}
