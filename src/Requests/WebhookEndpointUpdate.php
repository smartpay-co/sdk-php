<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Smartpay\Requests\Traits\RequestTrait;

/**
 * Class WebhookEndpointUpdate.
 */
class WebhookEndpointUpdate extends WebhookEndpoint
{
    use RequestTrait;

    /**
     * @throws InvalidRequestPayloadError
     */
    public function toRequest()
    {
        if (array_key_exists('eventSubscriptions', $this->rawPayload)) {
            self::validateEventSubscriptions($this->rawPayload['eventSubscriptions']);
        }

        return $this->normalize();
    }

    private function normalize()
    {
        return [
            'active' => $this->getOrNull($this->rawPayload, 'active'),
            'url' => $this->getOrNull($this->rawPayload, 'url'),
            'eventSubscriptions' => $this->getOrNull($this->rawPayload, 'eventSubscriptions'),
            'description' => $this->getOrNull($this->rawPayload, 'description'),
            'metadata' => $this->getOrNull($this->rawPayload, 'metadata')
        ];
    }
}
