<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Smartpay\Requests\Traits\OrderTrait;
use Smartpay\Requests\Traits\RequestTrait;

/**
 * Class CheckoutSessionForToken.
 */
class CheckoutSessionForToken
{
    use RequestTrait;
    use OrderTrait;

    const REQUIREMENT_KEY_NAME = ['successUrl', 'cancelUrl', 'customerInfo', 'mode'];
    const ALLOWED_LOCALE_VALUES = ['en', 'ja'];

    /**
     * @throws InvalidRequestPayloadError
     */
    public function toRequest()
    {
        if (!$this->requiredKeysExist($this->rawPayload, self::REQUIREMENT_KEY_NAME)) {
            throw new InvalidRequestPayloadError('Invalid request');
        }

        if ($this->rawPayload['mode'] != 'token') {
            throw new InvalidRequestPayloadError('Invalid request');
        }

        if (array_key_exists('locale', $this->rawPayload) &&
            !in_array($this->rawPayload['locale'], self::ALLOWED_LOCALE_VALUES)) {
            throw new InvalidRequestPayloadError('Invalid locale');
        }

        return $this->normalize();
    }

    protected function normalize()
    {
        return [
            'mode' => $this->getOrNull($this->rawPayload, 'mode'),
            'locale' => $this->getOrNull($this->rawPayload, 'locale'),
            'customerInfo' => $this->normalizeCustomerInfo(),
            'reference' => $this->getOrNull($this->rawPayload, 'reference'),
            'metadata' => $this->getOrNull($this->rawPayload, 'metadata'),
            'successUrl' => $this->getOrNull($this->rawPayload, 'successUrl'),
            'cancelUrl' => $this->getOrNull($this->rawPayload, 'cancelUrl'),
        ];
    }
}
