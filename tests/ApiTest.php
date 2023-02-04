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
            'successUrl' => 'https://mock.test/success_url',
            'cancelUrl' => 'https://mock.test/cancel_url',
            'items' => [],
            'currency' => 'JPY'
        ];

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], '{}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(new GuzzleClient(['handler' => $handlerStack]));

        $api = new Api('sk_test_mock', 'pk_test_mock', $client);
        $this->assertSame([], $api->checkoutSession($validPayload)->asJson());
    }

    public function testGetOrders()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], '{}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(new GuzzleClient(['handler' => $handlerStack]));

        $api = new Api('sk_test_mock', 'pk_test_mock', $client);
        $this->assertSame([], $api->getOrders()->asJson());
    }

    public function testCalculateWebhookSignature()
    {
        $api = new Api('MOCKSECRETKEY');
        $calculatedSignature = $api->calculateWebhookSignature("test data");
        $this->assertEquals('ae82b27dd6dafd0dbfc65faff07160833776cf60915782ef991557e51e4d1782', $calculatedSignature);
    }

    public function testValidateWebhookSignature()
    {
        $api = new Api('MOCKSECRETKEY');
        $signatureValid = $api->validateWebhookSignature("test data",
            "30f01ff4be78d2a2b053ad4a7922c4b4eb2aee75aa5326f2c9b84b52fe4e620e",
            "test timestamp"
        );
        $this->assertEquals(true, $signatureValid);
    }
}
