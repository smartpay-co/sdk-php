<?php

namespace Smartpay;

use Smartpay\Client;
use Smartpay\Smartpay;
use Smartpay\Requests\CheckoutSession as CheckoutSessionRequest;
use Smartpay\Responses\CheckoutSession as CheckoutSessionResponse;

/**
 * Class Smartpay.
 */
class Api
{
    private $client;

    public function __construct($publicKey, $secretKey, $client = Null)
    {
        Smartpay::setPublicKey($publicKey);
        Smartpay::setSecretKey($secretKey);

        $this->client = is_null($client) ? new Client() : $client;
    }

    public function checkoutSession($rawPayload)
    {
        $request = new CheckoutSessionRequest($rawPayload);
        return new CheckoutSessionResponse(
            $this->client->post('/checkout-sessions', $request->toRequest())
        );
    }
}
