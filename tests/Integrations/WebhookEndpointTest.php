<?php

namespace Tests\Integrations;

use Smartpay\Errors\InvalidRequestPayloadError;
use Tests\TestCase;

final class WebhookEndpointTest extends TestCase
{
    /**
     * @throws InvalidRequestPayloadError
     */
    public function testWebhookLifecycle()
    {
        $api = new \Smartpay\Api(getenv('SMARTPAY_SECRET_KEY'), getenv('SMARTPAY_PUBLIC_KEY'));

        // Create
        $webhookEndpoint = $api->createWebhookEndpoint(['url' => 'https://example.com'])->asJson();
        self::assertArrayHasKey('id', $webhookEndpoint);

        $webhookEndpointId = $webhookEndpoint['id'];

        // Update
        $updateWebhookEndpoint = $api->updateWebhookEndpoint([
            'id' => $webhookEndpointId,
            'url' => 'https://example.net',
            'active' => false
        ])->asJson();
        self::assertSame('https://example.net', $updateWebhookEndpoint['url']);

        // Get
        $getWebhookEndpoint = $api->getWebhookEndpoint(['id' => $webhookEndpointId]);
        self::assertSame(200, $getWebhookEndpoint->getStatusCode());
        self::assertSame(false, $getWebhookEndpoint->asJson()['active']);

        // Delete
        $deleteWebhookEndpoint = $api->deleteWebhookEndpoint(['id' => $webhookEndpointId]);
        self::assertSame(204, $deleteWebhookEndpoint->getStatusCode());

        // List
        $webhooksResponse = $api->getWebhookEndpoints()->asJson();
        self::assertSame('collection', $webhooksResponse['object']);
    }
}
