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

	public function get($path, $rawParams)
	{
		$params = array_merge($rawParams, $this->defaultParams());

		return $this->client->get(Smartpay::getApiUrl() . $path, ['query' => $params, 'headers' => $this->headers()]);
	}

	public function post($path, $payload)
	{
		$params = $this->defaultParams();

		return $this->client->post(Smartpay::getApiUrl() . $path, ['json' => $payload, 'query' => $params, 'headers' => $this->headers()]);
	}

	public function put($path, $payload)
	{
		$params = $this->defaultParams();

		return $this->client->post(Smartpay::getApiUrl() . $path, ['json' => $payload, 'query' => $params, 'headers' => $this->headers()]);
	}

	private function headers()
	{
		return [
			'Authorization' => 'Basic ' . strval(Smartpay::getSecretKey()),
			'Accept' => 'application/json',
			'ContentType' => 'application/json'
		];
	}

	private function defaultParams()
	{
		return [
			'dev-lang' => Smartpay::DEV_LANG,
			'sdk-version' => Smartpay::VERSION
		];
	}
}
