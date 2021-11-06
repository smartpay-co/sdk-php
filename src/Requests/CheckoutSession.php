<?php

namespace Smartpay\Requests;

/**
 * Class Smartpay.
 */
class CheckoutSession
{
    private $rawPayload;

    public function __construct($rawPayload)
    {
	$this->rawPayload = $rawPayload;
    }

    public function toRequest()
    {
	return $this->rawPayload;
    }
}
