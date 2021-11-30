<?php

namespace Smartpay\Responses;

use Smartpay\Smartpay;

/**
 * Class Smartpay.
 */
class CheckoutSession
{
    private $guzzleResponse;

    public function __construct($guzzlePayload)
    {
        $this->guzzlePayload = $guzzlePayload;
    }

    public function asJson()
    {
        return json_decode(strval($this->guzzlePayload->getBody()), true);
    }

    public function redirectUrl()
    {
        return Smartpay::getCheckoutUrl() . '/login?session-id=' . $this->asJson()['id'] . '&public-key=' . Smartpay::getPublicKey();
    }
}
