<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;

/**
 * Class CheckoutSessionForToken.
 */
class CheckoutSessionForToken extends CheckoutSession
{
	const REQUIREMENT_KEY_NAME = ['successUrl', 'cancelUrl', 'customerInfo', 'mode', 'tokenType'];
    const ALLOWED_LOCALE_VALUES = ['en', 'ja'];
    const ALLOWED_TOKEN_TYPE_VALUES = ['recurring', 'one-click', 'pre-order'];

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

        if ($this->rawPayload['mode'] != 'token') {
            throw new InvalidRequestPayloadError('Invalid request');
        }


        if (!in_array($this->rawPayload['tokenType'], self::ALLOWED_TOKEN_TYPE_VALUES)) {
            throw new InvalidRequestPayloadError('Invalid tokenType');
        }

        if (array_key_exists('locale', $this->rawPayload) &&
            !in_array($this->rawPayload['locale'], self::ALLOWED_LOCALE_VALUES)) {
            throw new InvalidRequestPayloadError('Invalid locale');
        }

		return $this->normalize();
	}

    protected function normalize()
	{
		$metadata = is_null($this->getOrNull($this->rawPayload, 'metadata')) ? null : $this->rawPayload['metadata'];

		return [
            'mode' => $this->getOrNull($this->rawPayload, 'mode'),
            'tokenType' => $this->getOrNull($this->rawPayload, 'tokenType'),
            'locale' => $this->getOrNull($this->rawPayload, 'locale'),
			'customerInfo' => $this->normalizeCustomerInfo(),
			'reference' => $this->getOrNull($this->rawPayload, 'reference'),
			'metadata' => $metadata,
			'successUrl' => $this->getOrNull($this->rawPayload, 'successUrl'),
			'cancelUrl' => $this->getOrNull($this->rawPayload, 'cancelUrl'),
		];
	}
}
