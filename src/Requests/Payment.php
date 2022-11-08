<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Smartpay\Requests\Traits\RequestTrait;

/**
 * Class Payment.
 */
class Payment
{
    use RequestTrait;

    const REQUIREMENT_KEY_NAME = ['order', 'amount', 'currency'];

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
            'order' => $this->getOrNull($this->rawPayload, 'order'),
            'amount' => $this->getOrNull($this->rawPayload, 'amount'),
            'currency' => $this->getOrNull($this->rawPayload, 'currency'),
            'cancelRemainder' => $this->getOrNull($this->rawPayload, 'cancelRemainder'),
            'reference' => $this->getOrNull($this->rawPayload, 'reference'),
            'description' => $this->getOrNull($this->rawPayload, 'description'),
            'metadata' => $this->getOrNull($this->rawPayload, 'metadata'),
        ];
    }
}
