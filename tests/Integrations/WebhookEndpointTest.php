<?php

namespace Tests\Integrations;

use Tests\TestCase;

use Smartpay\Smartpay;

use GuzzleHttp\Client as GuzzleClient;

final class WebhookEndpointTest extends TestCase
{
    public function testWebhookLifecycle()
    {
        $api = new \Smartpay\Api(getenv('SMARTPAY_SECRET_KEY'), getenv('SMARTPAY_PUBLIC_KEY'));


    }
}
