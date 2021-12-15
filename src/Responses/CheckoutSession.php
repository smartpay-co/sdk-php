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
        $session = $this->asJson();

        $url = Smartpay::getCheckoutUrl() . '/login?session-id=' . $session['id'] . '&public-key=' . Smartpay::getPublicKey();

        if (array_key_exists('metadata', $session) && array_key_exists('__promotion_code__', $session['metadata'])) {
            $url = $url . '&promotion-code=' . $session['metadata']['__promotion_code__'];
        }

        return $url;
    }
}
