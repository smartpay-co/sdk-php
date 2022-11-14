<?php

namespace Smartpay;

use Smartpay\Client;
use Smartpay\Smartpay;
use Smartpay\Requests\CheckoutSession as CheckoutSessionRequest;
use Smartpay\Requests\CheckoutSessionForToken as CheckoutSessionForTokenRequest;
use Smartpay\Requests\Order as OrderRequest;
use Smartpay\Requests\Payment as PaymentRequest;
use Smartpay\Requests\Refund as RefundRequest;
use Smartpay\Requests\WebhookEndpoint as WebhookEndpointRequest;

use Smartpay\Responses\Base as BaseResponse;
use Smartpay\Responses\CheckoutSession as CheckoutSessionResponse;

/**
 * Class Smartpay.
 */
class Api
{
    private $client;

    public function __construct($secretKey, $publicKey = null, $client = null)
    {
        Smartpay::setSecretKey($secretKey);

        if ($publicKey) {
            Smartpay::setPublicKey($publicKey);
        }

        $this->client = is_null($client) ? new Client() : $client;
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     */
    public function checkoutSession($rawPayload)
    {
        if (isset($rawPayload['mode']) && ($rawPayload['mode'] == "token")) {
            return $this->checkoutSessionForToken($rawPayload);
        }

        $request = new CheckoutSessionRequest($rawPayload);
        return new CheckoutSessionResponse(
            $this->client->post('/checkout-sessions', $request->toRequest())
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     */
    public function checkoutSessionForToken($rawPayload)
    {
        $request = new CheckoutSessionForTokenRequest($rawPayload);
        return new CheckoutSessionResponse(
            $this->client->post('/checkout-sessions', $request->toRequest())
        );
    }

    public function getOrders($params = [])
    {
        $parsedParams = [
            'pageToken' => isset($params['pageToken']) ? $params['pageToken'] : null,
            'maxResults' => isset($params['maxResults']) ? $params['maxResults'] : null,
            'expand' => isset($params['expand']) ? $params['expand'] : '',
        ];

        return new BaseResponse(
            $this->client->get('/orders', $parsedParams)
        );
    }

    public function getOrder($params = [])
    {
        $id = $params['id'];
        $parsedParams = [
            'expand' => isset($params['expand']) ? $params['expand'] : null,
        ];

        return new BaseResponse(
            $this->client->get("/orders/{$id}", $parsedParams)
        );
    }

    public function cancelOrder($params = [])
    {
        $id = $params['id'];
        return new BaseResponse(
            $this->client->put("/orders/{$id}/cancellation", [])
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     */
    public function createOrder($rawPayload)
    {
        $request = new OrderRequest($rawPayload);
        return new BaseResponse(
            $this->client->post('/orders', $request->toRequest())
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     */
    public function createPayment($rawPayload)
    {
        $request = new PaymentRequest($rawPayload);
        return new BaseResponse(
            $this->client->post('/payments', $request->toRequest())
        );
    }


    public function getPayment($params = [])
    {
        $id = $params['id'];
        $parsedParams = [
            'expand' => isset($params['expand']) ? $params['expand'] : null,
        ];

        return new BaseResponse(
            $this->client->get("/payments/{$id}", $parsedParams)
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     */
    public function capture($rawPayload)
    {
        return $this->createPayment($rawPayload);
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     */
    public function createRefund($rawPayload)
    {
        $request = new RefundRequest($rawPayload);
        return new BaseResponse(
            $this->client->post('/refunds', $request->toRequest())
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     */
    public function refund($rawPayload)
    {
        return $this->createRefund($rawPayload);
    }


    public function getRefund($params = [])
    {
        $id = $params['id'];
        $parsedParams = [
            'expand' => isset($params['expand']) ? $params['expand'] : null,
        ];

        return new BaseResponse(
            $this->client->get("/refunds/{$id}", $parsedParams)
        );
    }

    /**
     * Webhook Endpoint
     */

    /**
     * @throws Errors\InvalidRequestPayloadError
     */
    public function createWebhookEndpoint($rawPayload)
    {
        $request = new WebhookEndpointRequest($rawPayload);
        return new BaseResponse(
            $this->client->post('/webhook-endpoints', $request->toRequest())
        );
    }

    public function getWebhookEndpoint($params = [])
    {
        $id = $params['id'];

        return new BaseResponse(
            $this->client->get("/webhook-endpoints/{$id}")
        );
    }

    public function getWebhookEndpoints($params = [])
    {
        $parsedParams = [
            'pageToken' => isset($params['pageToken']) ? $params['pageToken'] : null,
            'maxResults' => isset($params['maxResults']) ? $params['maxResults'] : null,
        ];

        return new BaseResponse(
            $this->client->get('/webhook-endpoints', $parsedParams)
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     */
    public function updateWebhookEndpoint($rawPayload)
    {
        $id = $rawPayload['id'];
        unset($rawPayload['id']);
        if (array_key_exists('eventSubscriptions', $rawPayload)) {
            WebhookEndpointRequest::validateEventSubscriptions($rawPayload['eventSubscriptions']);
        }

        return new BaseResponse(
            $this->client->patch("/webhook-endpoints/{$id}", $rawPayload)
        );
    }

    public function deleteWebhookEndpoint($params)
    {
        $id = $params['id'];

        return new BaseResponse(
            $this->client->delete("/webhook-endpoints/{$id}")
        );
    }

    /**
     * Token
     */

    public function getToken($params)
    {
        $id = $params['id'];

        return new BaseResponse(
            $this->client->get("/tokens/{$id}")
        );
    }

    public function deleteToken($params)
    {
        $id = $params['id'];

        return new BaseResponse(
            $this->client->delete("/tokens/{$id}")
        );
    }

    public function getTokens($params = [])
    {
        $parsedParams = [
            'pageToken' => isset($params['pageToken']) ? $params['pageToken'] : null,
            'maxResults' => isset($params['maxResults']) ? $params['maxResults'] : null,
        ];

        return new BaseResponse(
            $this->client->get('/tokens', $parsedParams)
        );
    }

    public function enableToken($params)
    {
        $id = $params['id'];
        return new BaseResponse(
            $this->client->put("/tokens/{$id}/enable", [])
        );
    }

    public function disableToken($params)
    {
        $id = $params['id'];
        return new BaseResponse(
            $this->client->put("/tokens/{$id}/disable", [])
        );
    }
}
