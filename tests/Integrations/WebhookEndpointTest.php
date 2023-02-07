<?php

namespace Tests\Integrations;

use GuzzleHttp\Exception\GuzzleException;
use Smartpay\Errors\InvalidRequestPayloadError;

/**
 * @group integration
 */
final class WebhookEndpointTest extends BaseTestCase
{
    /**
     * @throws InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function testWebhookLifecycle()
    {
        $api = $this->getApiClient();

        // Create
        $webhookEndpoint = $api->createWebhookEndpoint(['url' => 'https://example.com'])->asJson();
        $this->assertArrayHasKey('id', $webhookEndpoint);

        $webhookEndpointId = $webhookEndpoint['id'];

        // Update
        $updateWebhookEndpoint = $api->updateWebhookEndpoint([
            'id' => $webhookEndpointId,
            'url' => 'https://example.net',
            'active' => false
        ])->asJson();
        $this->assertSame('https://example.net', $updateWebhookEndpoint['url']);

        // Get
        $getWebhookEndpoint = $api->getWebhookEndpoint(['id' => $webhookEndpointId]);
        $this->assertSame(200, $getWebhookEndpoint->getStatusCode());
        $this->assertSame(false, $getWebhookEndpoint->asJson()['active']);

        // Delete
        $deleteWebhookEndpoint = $api->deleteWebhookEndpoint(['id' => $webhookEndpointId]);
        $this->assertSame(204, $deleteWebhookEndpoint->getStatusCode());

        // List
        $webhooksResponse = $api->getWebhookEndpoints()->asJson();
        $this->assertSame('collection', $webhooksResponse['object']);
    }
}
