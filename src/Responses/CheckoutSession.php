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

    public function redirectUrl($options = [])
    {
        $session = $this->asJson();

        $url = $session['url'];

        if ($options && array_key_exists('promotionCode', $options)) {
            $url = $url . '&promotion-code=' . $options['promotionCode'];
        }

        return $url;
    }
}
