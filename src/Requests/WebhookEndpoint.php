<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;

/**
 * Class WebhookEndpoint.
 */
class WebhookEndpoint
{
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
        'merchant_user.password_reset'
    ];

    private $rawPayload;

    public function __construct($rawPayload)
    {
        $this->rawPayload = $rawPayload;
    }

    /**
     * @throws InvalidRequestPayloadError
     */
    public function toRequest()
    {
        for ($i = 0; $i < count(self::REQUIREMENT_KEY_NAME); ++$i) {
            if (!array_key_exists(self::REQUIREMENT_KEY_NAME[$i], $this->rawPayload)) {
                throw new InvalidRequestPayloadError('Invalid request');
            }
        }

        if (array_key_exists('eventSubscriptions', $this->rawPayload)) {
            foreach ($this->rawPayload['eventSubscriptions'] as $eventSubscription) {
                if (!in_array($eventSubscription, self::ALLOWED_EVENT_SUBSCRIPTIONS_VALUES)) {
                    throw new InvalidRequestPayloadError('Invalid eventSubscription: ' . $eventSubscription);
                }
            }
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

    private function getOrNull($array, $key)
    {
        $value = null;
        if (array_key_exists($key, $array)) {
            $value = $array[$key];
        }
        return $value;
    }
}
