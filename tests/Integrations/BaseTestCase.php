<?php

namespace Tests\Integrations;

use GuzzleHttp\Client as GuzzleClient;
use Smartpay\Smartpay;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected $httpClient;

    protected function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new GuzzleClient([
                'base_uri' => "https://" . getenv('API_BASE'),
                'timeout'  => Smartpay::getPostTimeout(),
            ]);
        }
        return $this->httpClient;
    }

    protected function userLoginAndGetAccessToken()
    {
        $loginPayload = [
            "emailAddress" => getenv('TEST_USERNAME'),
            "password" => getenv('TEST_PASSWORD')
        ];
        $loginResponse = $this->getHttpClient()->post('/consumers/auth/login', [
            'headers' => [
                'Accept' => 'application/json',
                'ContentType' => 'application/json'
            ],
            'json' => $loginPayload
        ]);
        $loginResponseData = json_decode(strval($loginResponse->getBody()), true);
        return $loginResponseData['accessToken'];
    }
}
