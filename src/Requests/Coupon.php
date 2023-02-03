<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Smartpay\Requests\Traits\RequestTrait;

/**
 * Class Coupon.
 */
class Coupon
{
    use RequestTrait;

    const REQUIREMENT_KEY_NAME = ['discountType', 'name'];
    const ALLOWED_DISCOUNT_TYPES = ['percentage', 'amount'];

    /**
     * @throws InvalidRequestPayloadError
     */
    public function toRequest()
    {
        if (!$this->requiredKeysExist($this->rawPayload, self::REQUIREMENT_KEY_NAME)) {
            throw new InvalidRequestPayloadError('Invalid request');
        }

        if (!in_array($this->rawPayload['discountType'], self::ALLOWED_DISCOUNT_TYPES)) {
            throw new InvalidRequestPayloadError('Invalid discountType: ' . $this->rawPayload['discountType']);
        }

        $requiredDiscountFieldName = 'discount' . ucfirst($this->rawPayload['discountType']);
        if (!array_key_exists($requiredDiscountFieldName, $this->rawPayload)) {
            throw new InvalidRequestPayloadError($requiredDiscountFieldName . ' is required');
        }

        if ($this->rawPayload['discountType'] == 'amount' && !array_key_exists('currency', $this->rawPayload)) {
            throw new InvalidRequestPayloadError( 'currency is required for amount discountType');
        }

        return $this->normalize();
    }

    private function normalize()
    {
        return [
            'currency' => $this->getOrNull($this->rawPayload, 'currency'),
            'discountAmount' => $this->getOrNull($this->rawPayload, 'discountAmount'),
            'discountPercentage' => $this->getOrNull($this->rawPayload, 'discountPercentage'),
            'discountType' => $this->getOrNull($this->rawPayload, 'discountType'),
            'expiresAt' => $this->getOrNull($this->rawPayload, 'expiresAt'),
            'maxRedemptionCount' => $this->getOrNull($this->rawPayload, 'maxRedemptionCount'),
            'metadata' => $this->getOrNull($this->rawPayload, 'metadata'),
            'name' => $this->getOrNull($this->rawPayload, 'name'),
        ];
    }
}
