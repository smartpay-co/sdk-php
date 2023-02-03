<?php

namespace Tests\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Tests\TestCase;
use Smartpay\Requests\WebhookEndpointUpdate;

final class WebhookEndpointUpdateTest extends TestCase
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

        $request = new WebhookEndpointUpdate($payload);
        $r = $request->toRequest();

        $this->assertEquals($request->toRequest(), [
            'active' => null,
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

        $request = new WebhookEndpointUpdate($payload);
        $r = $request->toRequest();

        $this->assertEquals($request->toRequest(), [
            'active' => null,
            'url' => 'https://example.com',
            'description' => null,
            'metadata' => null,
            'eventSubscriptions' => null
        ]);
    }

    public function testToRequestThrowsExceptionIfEventSubscriptionIsNotAllowed()
    {
        $payload = [
            'url' => 'https://example.com',
            'eventSubscriptions' => [ 'order.authorized', 'not allowed' ]
        ];

        $request = new WebhookEndpointUpdate($payload);
        $this->expectException(InvalidRequestPayloadError::class);
        $request->toRequest();
    }
}
