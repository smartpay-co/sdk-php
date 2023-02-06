<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Smartpay\Requests\Traits\RequestTrait;

/**
 * Class PromotionCodeUpdate.
 */
class PromotionCodeUpdate
{
    use RequestTrait;

    public function toRequest()
    {
        return $this->normalize();
    }

    private function normalize()
    {
        return [
            'active' => $this->getOrNull($this->rawPayload, 'active'),
            'metadata' => $this->getOrNull($this->rawPayload, 'metadata')
        ];
    }
}
