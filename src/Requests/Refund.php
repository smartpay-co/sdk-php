<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Smartpay\Requests\Traits\RequestTrait;

/**
 * Class Refund.
 */
class Refund
{
    use RequestTrait;

    const REQUIREMENT_KEY_NAME = ['payment', 'amount', 'currency', 'reason'];

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
            'payment' => $this->getOrNull($this->rawPayload, 'payment'),
            'amount' => $this->getOrNull($this->rawPayload, 'amount'),
            'currency' => $this->getOrNull($this->rawPayload, 'currency'),
            'reason' => $this->getOrNull($this->rawPayload, 'reason'),
            'reference' => $this->getOrNull($this->rawPayload, 'reference'),
            'description' => $this->getOrNull($this->rawPayload, 'description'),
            'metadata' => $this->getOrNull($this->rawPayload, 'metadata'),
        ];
    }
}
