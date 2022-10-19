<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Smartpay\Requests\Traits\OrderTrait;
use Smartpay\Requests\Traits\RequestTrait;

/**
 * Class Order.
 */
class Order
{
    use RequestTrait;
    use OrderTrait;

    const REQUIREMENT_KEY_NAME = ['successUrl', 'cancelUrl', 'customerInfo', 'shippingInfo', 'currency', 'items', 'token'];

    /**
     * @throws InvalidRequestPayloadError
     */
    public function toRequest()
    {
        if (!$this->requiredKeysExist($this->rawPayload, self::REQUIREMENT_KEY_NAME)) {
            throw new InvalidRequestPayloadError('Invalid request');
        }
        return $this->normalize();
    }

    protected function normalize()
    {
        return [
            'token' => $this->getOrNull($this->rawPayload, 'token'),
            'promotionCode' => $this->getOrNull($this->rawPayload, 'promotionCode'),
            'customerInfo' => $this->normalizeCustomerInfo(),
            'amount' => $this->getOrNull($this->rawPayload, 'amount'),
            'currency' => $this->getCurrency(),
            'captureMethod' => $this->getOrNull($this->rawPayload, 'captureMethod'),
            'shippingInfo' => $this->getShippingInfo(),
            'items' => $this->normalizeItemData($this->getOrNull($this->rawPayload, 'items')),
            'reference' => $this->getOrNull($this->rawPayload, 'reference'),
            'metadata' => $this->getOrNull($this->rawPayload, 'metadata'),
            'successUrl' => $this->getOrNull($this->rawPayload, 'successUrl'),
            'cancelUrl' => $this->getOrNull($this->rawPayload, 'cancelUrl'),
        ];
    }
}
