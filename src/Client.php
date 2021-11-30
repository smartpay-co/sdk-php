<?php

namespace Smartpay;

use Smartpay\Smartpay;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;

/**
 * Class Smartpay.
 */
class Client
{
	private $client;

	public function __construct($client = null)
	{
		$this->client = $client;

		if (is_null($client)) {
			$this->client = new GuzzleClient([
				'base_uri' => Smartpay::getApiUrl(),
				'timeout'  => Smartpay::getPostTimeout(),
			]);
		}
	}

	public function post($path, $rawPayload)
	{
		$payload = array_merge($rawPayload, $this->defaultPayload());
		return $this->client->post(Smartpay::getApiUrl() . $path, ['json' => $payload, 'headers' => $this->headers()]);
	}

	private function headers()
	{
		return [
			'Authorization' => 'Basic ' . strval(Smartpay::getSecretKey()),
			'Accept' => 'application/json',
			'ContentType' => 'application/json'
		];
	}

	private function defaultPayload()
	{
		return [
			'dev-lang' => Smartpay::DEV_LANG,
			'sdk-version' => Smartpay::VERSION
		];
	}
}
