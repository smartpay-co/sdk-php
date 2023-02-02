<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Smartpay\Requests\Traits\RequestTrait;

/**
 * Class Refund.
 */
class RefundUpdate
{
    use RequestTrait;

    public function toRequest()
    {
        return $this->normalize();
    }

    private function normalize()
    {
        return [
            'reference' => $this->getOrNull($this->rawPayload, 'reference'),
            'description' => $this->getOrNull($this->rawPayload, 'description'),
            'metadata' => $this->getOrNull($this->rawPayload, 'metadata'),
        ];
    }
}
