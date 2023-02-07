<?php

namespace Smartpay;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Smartpay\Smartpay;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;

/**
 * Class Smartpay.
 */
class Client
{
    const DEFAULT_POST_TIMEOUT = 30;

    private $client;
    private $smartpay;

    /**
     * @param $client
     * @param $smartpay
     * @throws Exception
     */
    public function __construct($client = null, $smartpay = null)
    {
        if (is_null($smartpay)) {
            $smartpay = new Smartpay();
        }

        if (is_null($client)) {
            $stack = HandlerStack::create();
            $stack->push(GuzzleRetryMiddleware::factory());

            $client = new GuzzleClient([
                'handler' => $stack,
                'timeout'  => self::DEFAULT_POST_TIMEOUT,
                'max_retry_attempts' => 1,
                'retry_on_status' => [500, 502, 503, 504]
            ]);
        }

        $this->client = $client;
        $this->smartpay = $smartpay;
    }

    /**
     * @throws GuzzleException
     */
    public function get($path, $rawParams = [])
    {
        $params = array_merge($rawParams, $this->defaultParams());

        return $this->client->get($this->smartpay->getApiUrl() . $path, ['query' => $params, 'headers' => $this->headers()]);
    }

    /**
     * @throws GuzzleException
     */
    public function post($path, $payload, $idempotencyKey = null)
    {
        $params = $this->defaultParams();
        $headers = $this->headers();
        $headers['Idempotency-Key'] = $idempotencyKey ?: $this->nonce();

        return $this->client->post($this->smartpay->getApiUrl() . $path, ['json' => $payload, 'query' => $params, 'headers' => $headers]);
    }

    /**
     * @throws GuzzleException
     */
    public function put($path, $payload, $idempotencyKey = null)
    {
        $params = $this->defaultParams();
        $headers = $this->headers();
        $headers['Idempotency-Key'] = $idempotencyKey ?: $this->nonce();

        return $this->client->put($this->smartpay->getApiUrl() . $path, ['json' => $payload, 'query' => $params, 'headers' => $headers]);
    }

    /**
     * @throws GuzzleException
     */
    public function patch($path, $payload = [], $idempotencyKey = null)
    {
        $params = $this->defaultParams();
        $headers = $this->headers();
        $headers['Idempotency-Key'] = $idempotencyKey ?: $this->nonce();

        return $this->client->patch($this->smartpay->getApiUrl() . $path, ['json' => $payload, 'query' => $params, 'headers' => $headers]);
    }

    /**
     * @throws GuzzleException
     */
    public function delete($path, $payload = [], $idempotencyKey = null)
    {
        $params = $this->defaultParams();
        $headers = $this->headers();
        $headers['Idempotency-Key'] = $idempotencyKey ?: $this->nonce();

        return $this->client->delete($this->smartpay->getApiUrl() . $path, ['json' => $payload, 'query' => $params, 'headers' => $headers]);
    }

    private function headers()
    {
        return [
            'Authorization' => 'Basic ' . strval($this->smartpay->getSecretKey()),
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

    private function nonce()
    {
        $factory = new \RandomLib\Factory();
        $generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));

        return $generator->generateString(32, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
    }
}
