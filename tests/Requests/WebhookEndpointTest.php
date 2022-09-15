<?php

namespace Tests\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Tests\TestCase;
use Smartpay\Requests\WebhookEndpoint;

final class WebhookEndpointTest extends TestCase
{
    public function testToRequest()
    {
        $payload = [
            'url' => 'https://example.com',
            'metadata' => [
                'abc' => '123',
                'def' => '456'
            ],
            'eventSubscriptions' => [ 'order.authorized' ]
        ];

        $request = new WebhookEndpoint($payload);
        $r = $request->toRequest();

        $this->assertEquals($request->toRequest(), [
            'url' => 'https://example.com',
            'description' => null,
            'metadata' => [
                'abc' => '123',
                'def' => '456'
            ],
            'eventSubscriptions' => [ 'order.authorized' ]
        ]);
    }

    public function testToRequestWithNullMetadataAndEventSubscriptions()
    {
        $payload = [
            'url' => 'https://example.com'
        ];

        $request = new WebhookEndpoint($payload);
        $r = $request->toRequest();

        $this->assertEquals($request->toRequest(), [
            'url' => 'https://example.com',
            'description' => null,
            'metadata' => null,
            'eventSubscriptions' => null
        ]);
    }

    public function testToRequestThrowsExceptionIfUrlIsMissing()
    {
        $payload = [];

        $request = new WebhookEndpoint($payload);
        $this->expectException(InvalidRequestPayloadError::class);
        $request->toRequest();
    }

    public function testToRequestThrowsExceptionIfEventScriptionIsNotAllowed()
    {
        $payload = [
            'url' => 'https://example.com',
            'eventSubscriptions' => [ 'order.authorized', 'not allowed' ]
        ];

        $request = new WebhookEndpoint($payload);
        $this->expectException(InvalidRequestPayloadError::class);
        $request->toRequest();
    }
}
