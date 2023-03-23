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
        $calculatedSignature = $api->calculateWebhookSignature("test data", "MOCKSECRETKEY");
        $this->assertEquals('ae82b27dd6dafd0dbfc65faff07160833776cf60915782ef991557e51e4d1782', $calculatedSignature);
    }

    public function testValidateWebhookSignature()
    {
        $data = '{"id":"evt_test_dwPfFKu5iSEKyHR2LFj9Lx","object":"event","createdAt":1653028523052,"test":true,"eventData":{"type":"payment.created","version":"2022-02-18","data":{"id":"payment_test_35LxgmF5KM22XKG38BjpJg","object":"payment","test":true,"createdAt":1653028523020,"updatedAt":1653028523020,"amount":200,"currency":"JPY","order":"order_test_RiYq2rthzRHrkKVGeucSwn","reference":"order_ref_1234567","status":"processed","metadata":{}}}}';
        $timestamp = '1653028612220';
        $signature = '68007ada8485ea0ceca7c5e879ae860a50412b7af95ab8e81b32a3e13f3c0832';
        $secret = 'gybcsjixKyBW2d4z6iNPlaYzHUMtawnodwZt3W0q';

        $api = new Api('MOCKSECRETKEY');
        $signatureValid = $api->validateWebhookSignature(
            $data,
            $signature,
            $timestamp,
            $secret
        );
        $this->assertEquals(true, $signatureValid);
    }
}
