<?php

namespace Tests\Smartpay;

use Tests\TestCase;

use Smartpay\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class ClientTest extends TestCase
{
    public function testPost()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'Hello, World'),
        ]);
        $handlerStack = HandlerStack::create($mock);

        $client = new Client(new GuzzleClient(['handler' => $handlerStack]));
        $this->assertSame('Hello, World', strval($client->post('/test', ['raw' => 'data'])->getBody()));
    }
}
