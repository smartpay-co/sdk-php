<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Smartpay\Requests\Traits\RequestTrait;

/**
 * Class PromotionCode.
 */
class PromotionCode
{
    use RequestTrait;

    const REQUIREMENT_KEY_NAME = ['code', 'coupon'];

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

    private function normalize()
    {
        return [
            'active' => $this->getOrNull($this->rawPayload, 'active'),
            'code' => $this->getOrNull($this->rawPayload, 'code'),
            'coupon' => $this->getOrNull($this->rawPayload, 'coupon'),
            'currency' => $this->getOrNull($this->rawPayload, 'currency'),
            'expiresAt' => $this->getOrNull($this->rawPayload, 'expiresAt'),
            'firstTimeTransaction' => $this->getOrNull($this->rawPayload, 'firstTimeTransaction'),
            'maxRedemptionCount' => $this->getOrNull($this->rawPayload, 'maxRedemptionCount'),
            'metadata' => $this->getOrNull($this->rawPayload, 'metadata'),
            'minimumAmount' => $this->getOrNull($this->rawPayload, 'minimumAmount'),
            'onePerCustomer' => $this->getOrNull($this->rawPayload, 'onePerCustomer')
        ];
    }
}
