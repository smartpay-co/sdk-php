<?php

namespace Smartpay;

use Smartpay\Smartpay;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;

function nonce()
{
	$factory = new \RandomLib\Factory();
	$generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));

	return $generator->generateString(32, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
}

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
			$stack = HandlerStack::create();
			$stack->push(GuzzleRetryMiddleware::factory());

			$this->client = new GuzzleClient([
				'handler' => $stack,
				'base_uri' => Smartpay::getApiUrl(),
				'timeout'  => Smartpay::getPostTimeout(),
				'max_retry_attempts' => 1,
				'retry_on_status' => [500, 502, 503, 504]
			]);
		}
	}

	public function get($path, $rawParams = [])
	{
		$params = array_merge($rawParams, $this->defaultParams());

		return $this->client->get(Smartpay::getApiUrl() . $path, ['query' => $params, 'headers' => $this->headers()]);
	}

	public function post($path, $payload)
	{
		$params = $this->defaultParams();
		$headers = $this->headers();
		$headers['Idempotency-Key'] = nonce();

		return $this->client->post(Smartpay::getApiUrl() . $path, ['json' => $payload, 'query' => $params, 'headers' => $headers]);
	}

	public function put($path, $payload)
	{
		$params = $this->defaultParams();
		$headers = $this->headers();
		$headers['Idempotency-Key'] = nonce();

		return $this->client->put(Smartpay::getApiUrl() . $path, ['json' => $payload, 'query' => $params, 'headers' => $headers]);
	}

    public function patch($path, $payload = [])
    {
        $params = $this->defaultParams();
        $headers = $this->headers();
        $headers['Idempotency-Key'] = nonce();

        return $this->client->patch(Smartpay::getApiUrl() . $path, ['json' => $payload, 'query' => $params, 'headers' => $headers]);
    }

    public function delete($path, $payload = [])
    {
        $params = $this->defaultParams();
        $headers = $this->headers();
        $headers['Idempotency-Key'] = nonce();

        return $this->client->delete(Smartpay::getApiUrl() . $path, ['json' => $payload, 'query' => $params, 'headers' => $headers]);
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
