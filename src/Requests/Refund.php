<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;

/**
 * Class Refund.
 */
class Refund
{
	const REQUIREMENT_KEY_NAME = ['payment', 'amount', 'currency'];

	private $rawPayload;

	public function __construct($rawPayload)
	{
		$this->rawPayload = $rawPayload;
	}

	public function toRequest()
	{
		for ($i = 0; $i < count(Refund::REQUIREMENT_KEY_NAME); ++$i) {
			if (!array_key_exists(Refund::REQUIREMENT_KEY_NAME[$i], $this->rawPayload)) {
				throw new InvalidRequestPayloadError('Invalid request');
			}
		}

		$this->normalizePayload = $this->normalize();
		return $this->normalizePayload;
	}

	private function normalize()
	{
		$metadata = is_null($this->getOrNull($this->rawPayload, 'metadata')) ? null : $this->rawPayload['metadata'];

		return [
			'payment' => $this->getOrNull($this->rawPayload, 'payment'),
			'amount' => $this->getOrNull($this->rawPayload, 'amount'),
			'currency' => $this->getOrNull($this->rawPayload, 'currency'),
			'reason' => $this->getOrNull($this->rawPayload, 'reason'),
			'reference' => $this->getOrNull($this->rawPayload, 'reference'),
			'description' => $this->getOrNull($this->rawPayload, 'description'),
			'metadata' => $metadata,
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
