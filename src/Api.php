<?php

namespace Smartpay;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Smartpay\Client;
use Smartpay\Requests\Coupon;
use Smartpay\Requests\PromotionCode;
use Smartpay\Smartpay;
use Smartpay\Requests\CheckoutSession as CheckoutSessionRequest;
use Smartpay\Requests\CheckoutSessionForToken as CheckoutSessionForTokenRequest;
use Smartpay\Requests\Order as OrderRequest;
use Smartpay\Requests\Payment as PaymentRequest;
use Smartpay\Requests\PaymentUpdate as PaymentUpdateRequest;
use Smartpay\Requests\Refund as RefundRequest;
use Smartpay\Requests\RefundUpdate as RefundUpdateRequest;
use Smartpay\Requests\Coupon as CouponRequest;
use Smartpay\Requests\CouponUpdate as CouponUpdateRequest;
use Smartpay\Requests\PromotionCode as PromotionCodeRequest;
use Smartpay\Requests\PromotionCodeUpdate as PromotionCodeUpdateRequest;
use Smartpay\Requests\WebhookEndpoint as WebhookEndpointRequest;
use Smartpay\Requests\WebhookEndpointUpdate as WebhookEndpointUpdateRequest;

use Smartpay\Responses\Base as BaseResponse;
use Smartpay\Responses\CheckoutSession as CheckoutSessionResponse;
use Tuupola\Base62;

/**
 * Class Smartpay.
 */
class Api
{
    private $client;
    private $smartpay;

    /**
     * @throws Exception
     */
    public function __construct($secretKey = null, $publicKey = null, $client = null)
    {
        $this->smartpay = new Smartpay($secretKey, $publicKey);


        $this->client = is_null($client) ? new Client(null, $this->smartpay) : $client;
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function checkoutSession($rawPayload, $idempotencyKey = null)
    {
        if (isset($rawPayload['mode']) && ($rawPayload['mode'] == "token")) {
            return $this->checkoutSessionForToken($rawPayload, $idempotencyKey);
        }

        $request = new CheckoutSessionRequest($rawPayload);
        return new CheckoutSessionResponse(
            $this->client->post('/checkout-sessions', $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function checkoutSessionForToken($rawPayload, $idempotencyKey = null)
    {
        $request = new CheckoutSessionForTokenRequest($rawPayload);
        return new CheckoutSessionResponse(
            $this->client->post('/checkout-sessions', $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getCheckoutSession($params = [])
    {
        list($id, $parsedParams) = $this->parseExpandableObjectParams($params);
        return new BaseResponse(
            $this->client->get("/checkout-sessions/{$id}", $parsedParams)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getCheckoutSessions($params = [])
    {
        return new BaseResponse(
            $this->client->get('/checkout-sessions', $this->parseCollectionParams($params))
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getOrders($params = [])
    {
        return new BaseResponse(
            $this->client->get('/orders', $this->parseCollectionParams($params))
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getOrder($params = [])
    {
        list($id, $parsedParams) = $this->parseExpandableObjectParams($params);

        return new BaseResponse(
            $this->client->get("/orders/{$id}", $parsedParams)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function cancelOrder($params = [], $idempotencyKey = null)
    {
        $id = $params['id'];
        return new BaseResponse(
            $this->client->put("/orders/{$id}/cancellation", [], $idempotencyKey)
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function createOrder($rawPayload, $idempotencyKey = null)
    {
        $request = new OrderRequest($rawPayload);
        return new BaseResponse(
            $this->client->post('/orders', $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function createPayment($rawPayload, $idempotencyKey = null)
    {
        $request = new PaymentRequest($rawPayload);
        return new BaseResponse(
            $this->client->post('/payments', $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function updatePayment($rawPayload, $idempotencyKey = null)
    {
        $id = $rawPayload['id'];
        unset($rawPayload['id']);
        $request = new PaymentUpdateRequest($rawPayload);

        return new BaseResponse(
            $this->client->patch("/payments/{$id}", $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getPayment($params = [])
    {
        list($id, $parsedParams) = $this->parseExpandableObjectParams($params);

        return new BaseResponse(
            $this->client->get("/payments/{$id}", $parsedParams)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getPayments($params = [])
    {
        return new BaseResponse(
            $this->client->get('/payments', $this->parseCollectionParams($params))
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function capture($rawPayload, $idempotencyKey = null)
    {
        return $this->createPayment($rawPayload, $idempotencyKey);
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function createRefund($rawPayload, $idempotencyKey = null)
    {
        $request = new RefundRequest($rawPayload);
        return new BaseResponse(
            $this->client->post('/refunds', $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function refund($rawPayload, $idempotencyKey = null)
    {
        return $this->createRefund($rawPayload, $idempotencyKey);
    }

    /**
     * @throws GuzzleException
     */
    public function updateRefund($rawPayload, $idempotencyKey = null)
    {
        $id = $rawPayload['id'];
        unset($rawPayload['id']);
        $request = new RefundUpdateRequest($rawPayload);

        return new BaseResponse(
            $this->client->patch("/refunds/{$id}", $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getRefund($params = [])
    {
        list($id, $parsedParams) = $this->parseExpandableObjectParams($params);

        return new BaseResponse(
            $this->client->get("/refunds/{$id}", $parsedParams)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getRefunds($params = [])
    {
        return new BaseResponse(
            $this->client->get('/refunds', $this->parseCollectionParams($params))
        );
    }

    /**
     * Coupon
     */

    /**
     * @throws GuzzleException
     */
    public function createCoupon($rawPayload, $idempotencyKey = null)
    {
        $request = new CouponRequest($rawPayload);
        return new BaseResponse(
            $this->client->post('/coupons', $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getCoupon($params = [])
    {
        $id = $params['id'];
        return new BaseResponse(
            $this->client->get("/coupons/{$id}")
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getCoupons($params = [])
    {
        return new BaseResponse(
            $this->client->get('/coupons', $this->parseCollectionParams($params))
        );
    }

    /**
     * @throws GuzzleException
     */
    public function updateCoupon($rawPayload, $idempotencyKey = null)
    {
        $id = $rawPayload['id'];
        unset($rawPayload['id']);
        $request = new CouponUpdateRequest($rawPayload);
        return new BaseResponse(
            $this->client->patch("/coupons/{$id}", $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * PromotionCode
     */

    /**
     * @throws Errors\InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function createPromotionCode($rawPayload, $idempotencyKey = null)
    {
        $request = new PromotionCodeRequest($rawPayload);
        return new BaseResponse(
            $this->client->post('/promotion-codes', $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getPromotionCode($params = [])
    {
        $id = $params['id'];
        return new BaseResponse(
            $this->client->get("/promotion-codes/{$id}")
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getPromotionCodes($params = [])
    {
        return new BaseResponse(
            $this->client->get('/promotion-codes', $this->parseCollectionParams($params))
        );
    }

    /**
     * @throws GuzzleException
     */
    public function updatePromotionCode($rawPayload, $idempotencyKey = null)
    {
        $id = $rawPayload['id'];
        unset($rawPayload['id']);
        $request = new PromotionCodeUpdateRequest($rawPayload);
        return new BaseResponse(
            $this->client->patch("/promotion-codes/{$id}", $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * Webhook Endpoint
     */

    /**
     * @throws Errors\InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function createWebhookEndpoint($rawPayload, $idempotencyKey = null)
    {
        $request = new WebhookEndpointRequest($rawPayload);
        return new BaseResponse(
            $this->client->post('/webhook-endpoints', $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getWebhookEndpoint($params = [])
    {
        $id = $params['id'];

        return new BaseResponse(
            $this->client->get("/webhook-endpoints/{$id}")
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getWebhookEndpoints($params = [])
    {
        return new BaseResponse(
            $this->client->get('/webhook-endpoints', $this->parseCollectionParams($params))
        );
    }

    /**
     * @throws Errors\InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function updateWebhookEndpoint($rawPayload, $idempotencyKey = null)
    {
        $id = $rawPayload['id'];
        unset($rawPayload['id']);
        $request = new WebhookEndpointUpdateRequest($rawPayload);

        return new BaseResponse(
            $this->client->patch("/webhook-endpoints/{$id}", $request->toRequest(), $idempotencyKey)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function deleteWebhookEndpoint($params, $idempotencyKey = null)
    {
        $id = $params['id'];

        return new BaseResponse(
            $this->client->delete("/webhook-endpoints/{$id}", $idempotencyKey)
        );
    }

    /**
     * Token
     */

    /**
     * @throws GuzzleException
     */
    public function getToken($params)
    {
        $id = $params['id'];

        return new BaseResponse(
            $this->client->get("/tokens/{$id}")
        );
    }

    /**
     * @throws GuzzleException
     */
    public function deleteToken($params, $idempotencyKey = null)
    {
        $id = $params['id'];

        return new BaseResponse(
            $this->client->delete("/tokens/{$id}", $idempotencyKey)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getTokens($params = [])
    {
        return new BaseResponse(
            $this->client->get('/tokens', $this->parseCollectionParams($params))
        );
    }

    /**
     * @throws GuzzleException
     */
    public function enableToken($params, $idempotencyKey = null)
    {
        $id = $params['id'];
        return new BaseResponse(
            $this->client->put("/tokens/{$id}/enable", [], $idempotencyKey)
        );
    }

    /**
     * @throws GuzzleException
     */
    public function disableToken($params, $idempotencyKey = null)
    {
        $id = $params['id'];
        return new BaseResponse(
            $this->client->put("/tokens/{$id}/disable", [], $idempotencyKey)
        );
    }

    /**
     * Webhook Signature Helpers
     */

    public function calculateWebhookSignature($data)
    {
        $base62 = new Base62(["characters" => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789']);
        return hash_hmac('sha256', $data, $base62->decode($this->smartpay->getSecretKey()));
    }

    public function validateWebhookSignature($data, $signature, $signatureTimestamp)
    {
        $calculatedSignature = $this->calculateWebhookSignature($signatureTimestamp . '.' . $data);
        return $signature === $calculatedSignature;
    }

    /**
     * @param $params
     * @return array
     */
    private function parseCollectionParams($params)
    {
        return [
            'pageToken' => isset($params['pageToken']) ? $params['pageToken'] : null,
            'maxResults' => isset($params['maxResults']) ? $params['maxResults'] : null,
            'expand' => isset($params['expand']) ? $params['expand'] : '',
        ];
    }

    /**
     * @param $params
     * @return array
     */
    private function parseExpandableObjectParams($params)
    {
        $id = $params['id'];
        $parsedParams = [
            'expand' => isset($params['expand']) ? $params['expand'] : null,
        ];
        return array($id, $parsedParams);
    }
}
