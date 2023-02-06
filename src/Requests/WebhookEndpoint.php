<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Smartpay\Requests\Traits\RequestTrait;

/**
 * Class WebhookEndpoint.
 */
class WebhookEndpoint
{
    use RequestTrait;

    const REQUIREMENT_KEY_NAME = ['url'];
    const ALLOWED_EVENT_SUBSCRIPTIONS_VALUES = [
        'order.authorized',
        'order.rejected',
        'order.canceled',
        'payment.created',
        'refund.created',
        'payout.generated',
        'payout.paid',
        'coupon.created',
        'coupon.updated',
        'promotion_code.created',
        'promotion_code.updated',
        'merchant_user.created',
        'merchant_user.password_reset',
        "token.approved",
        "token.rejected",
        "token.deleted",
        "token.disabled",
        "token.enabled",
        "token.created",
        "token.used"
    ];

    /**
     * @throws InvalidRequestPayloadError
     */
    public function toRequest()
    {
        if (!$this->requiredKeysExist($this->rawPayload, self::REQUIREMENT_KEY_NAME)) {
            throw new InvalidRequestPayloadError('Invalid request');
        }

        if (array_key_exists('eventSubscriptions', $this->rawPayload)) {
            self::validateEventSubscriptions($this->rawPayload['eventSubscriptions']);
        }

        return $this->normalize();
    }

    private function normalize()
    {
        return [
            'url' => $this->getOrNull($this->rawPayload, 'url'),
            'eventSubscriptions' => $this->getOrNull($this->rawPayload, 'eventSubscriptions'),
            'description' => $this->getOrNull($this->rawPayload, 'description'),
            'metadata' => $this->getOrNull($this->rawPayload, 'metadata')
        ];
    }

    /**
     * @throws InvalidRequestPayloadError
     */
    public static function validateEventSubscriptions($eventSubscriptions)
    {
        foreach ($eventSubscriptions as $eventSubscription) {
            if (!in_array($eventSubscription, self::ALLOWED_EVENT_SUBSCRIPTIONS_VALUES)) {
                throw new InvalidRequestPayloadError('Invalid eventSubscription: ' . $eventSubscription);
            }
        }
    }
}
