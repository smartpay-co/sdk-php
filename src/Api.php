<?php

namespace Smartpay;

use Smartpay\Client;
use Smartpay\Smartpay;
use Smartpay\Requests\CheckoutSession as CheckoutSessionRequest;
use Smartpay\Responses\CheckoutSession as CheckoutSessionResponse;
use Smartpay\Requests\Payment as PaymentRequest;
use Smartpay\Requests\Refund as RefundRequest;
use Smartpay\Responses\Base as BaseResponse;

/**
 * Class Smartpay.
 */
class Api
{
    private $client;

    public function __construct($secretKey, $publicKey = Null, $client = Null)
    {
        Smartpay::setSecretKey($secretKey);

        if ($publicKey) {
            Smartpay::setPublicKey($publicKey);
        }

        $this->client = is_null($client) ? new Client() : $client;
    }

    public function checkoutSession($rawPayload)
    {
        $request = new CheckoutSessionRequest($rawPayload);
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

    public function capture($rawPayload)
    {
        return $this->createPayment($rawPayload);
    }

    public function createRefund($rawPayload)
    {
        $request = new RefundRequest($rawPayload);
        return new BaseResponse(
            $this->client->post('/refunds', $request->toRequest())
        );
    }

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
}
