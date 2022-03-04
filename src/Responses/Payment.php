<?php

namespace Smartpay\Responses;

use Smartpay\Smartpay;

class Payment
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
}
