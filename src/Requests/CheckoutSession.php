<?php

namespace Smartpay\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;

/**
 * Class Smartpay.
 */
class CheckoutSession
{
	const REQUIREMENT_KEY_NAME = ['successURL', 'cancelURL', 'customerInfo', 'orderData'];
	const CAN_FALLBACK_KEYS = ['customerInfo', 'orderData'];

	private $rawPayload;

	private $item = [];
	private $currency = '';
	private $amount = 0.0;

	public function __construct($rawPayload)
	{
		$this->rawPayload = $rawPayload;
	}

	public function toRequest()
	{
		for ($i = 0; $i < count(CheckoutSession::REQUIREMENT_KEY_NAME); ++$i) {
			if (!array_key_exists(CheckoutSession::REQUIREMENT_KEY_NAME[$i], $this->rawPayload)) {
				if (!in_array(CheckoutSession::REQUIREMENT_KEY_NAME[$i], CheckoutSession::CAN_FALLBACK_KEYS)) {
					throw new InvalidRequestPayloadError('Invalid request');
				}
			}
		}

		$this->setItem();
		$this->setCurrency();
		$this->setTotalAmount();

		$this->normalizePayload = $this->normalize();
		return $this->normalizePayload;
	}

	private function setItem()
	{
		if (array_key_exists('orderData', $this->rawPayload)) {
			if (array_key_exists('$this->lineItemData', $this->rawPayload['orderData'])) {
				if (array_key_exists('priceData', $this->rawPayload['orderData']['lineItemData'])) {
					$this->item = $this->rawPayload['orderData']['lineItemData']['priceData'];
				}
			}
		}

		if (count($this->item) === 0) {
			if (array_key_exists('items', $this->rawPayload)) {
				$this->item = $this->rawPayload['items'];
			}
		}
	}

	private function setCurrency()
	{
		if (array_key_exists('orderData', $this->rawPayload)) {
			if (array_key_exists('currency', $this->rawPayload['orderData'])) {
				$this->currency = $this->rawPayload['orderData']['currency'];
			}
		}

		if (strlen($this->currency) === 0) {
			if (count($this->item) > 0) {
				if (array_key_exists('currency', $this->item[0])) {
					$this->currency = $this->item[0]['currency'];
				}
			}
		}
	}

	private function setTotalAmount()
	{
		if (array_key_exists('orderData', $this->rawPayload)) {
			if (array_key_exists('amount', $this->rawPayload['orderData'])) {
				$this->amount = $this->rawPayload['orderData']['amount'];

				return;
			}
		}

		if (
			array_key_exists('shipping', $this->rawPayload) &&
			array_key_exists('feeAmount', $this->rawPayload['shipping'])
		) {
			$this->amount = $this->rawPayload['shipping']['feeAmount'];
		}

		if (
			array_key_exists('orderData', $this->rawPayload) &&
			array_key_exists('shippingInfo', $this->rawPayload['orderData']) &&
			array_key_exists('feeAmount', $this->rawPayload['orderData']['shippingInfo'])
		) {
			$this->amount = $this->rawPayload['orderData']['shippingInfo']['amount'];
		}

		if (count($this->item) > 0) {
			for ($i = 0; $i < count($this->item); ++$i) {
				if (array_key_exists('amount', $this->item[$i])) {
					$this->amount += $this->item[$i]['amount'];
				}
			}
		}
	}

	private function normalize()
	{
		return [
			'customerInfo' => $this->normalizeCustomerInfo(),
			'orderData' => $this->normalizeOrderData(),
			'reference' => $this->getOrNull($this->rawPayload, 'reference'),
			'metadata' => $this->getOrNull($this->rawPayload, 'metadata'),
			'successUrl' => $this->getOrNull($this->rawPayload, 'successURL'),
			'cancelUrl' => $this->getOrNull($this->rawPayload, 'cancelURL'),
			'test' => $this->getOrNull($this->rawPayload, 'test')
		];
	}

	private function normalizeCustomerInfo()
	{
		$data = [];
		if (array_key_exists('customerInfo', $this->rawPayload)) {
			$data = $this->rawPayload['customerInfo'];
		} else if (array_key_exists('customer', $this->rawPayload)) {
			$data = $this->rawPayload['customer'];
		}

		$email = $this->getOrNull($data, 'emailAddress');
		$phone = $this->getOrNull($data, 'phoneNumber');
		$gender = $this->getOrNull($data, 'legalGender');
		return [
			'accountAge' => $this->getOrNull($data, 'accountAge'),
			'emailAddress' => is_null($email) ? $this->getOrNull($data, 'email') : $email,
			'firstName' => $this->getOrNull($data, 'firstName'),
			'lastName' => $this->getOrNull($data, 'lastName'),
			'firstNameKana' => $this->getOrNull($data, 'firstNameKana'),
			'lastNameKana' => $this->getOrNull($data, 'lastNameKana'),
			'address' => $this->getOrNull($data, 'address'),
			'phoneNumber' => is_null($phone) ? $this->getOrNull($data, 'phone') : $phone,
			'dateOfBirth' => $this->getOrNull($data, 'dateOfBirth'),
			'legalGender' => is_null($gender) ? $this->getOrNull($data, 'gender') : $gender,
			'reference' => $this->getOrNull($data, 'reference')
		];
	}

	private function normalizeOrderData()
	{
		$shiippingInfo = is_null($this->getOrNull($this->rawPayload, 'shippingInfo'))
			? $this->normalizeShippingInfo($this->getOrNull($this->rawPayload, 'shipping'))
			: $this->rawPayload['shippingInfo'];

		$items = is_null($this->getOrNull($this->rawPayload, 'lineItemData'))
			? $this->normalizeItemData($this->getOrNull($this->rawPayload, 'items'))
			: $this->normalizeItemData($this->rawPayload['lineItemData']);

		return [
			'amount' => $this->amount,
			'currency' => $this->currency,
			'captureMethod' => $this->getOrNull($this->rawPayload, 'captureMethod'),
			'confirmationMethod' => $this->getOrNull($this->rawPayload, 'confirmationMethod'),
			'coupons' => $this->getOrNull($this->rawPayload, 'coupons'),
			'shippingInfo' => $shiippingInfo,
			'lineItemData' => $items,
		];
	}

	private function normalizeShippingInfo($data)
	{
		if (is_null($data)) {
			return null;
		}

		$address = is_null($this->getOrNull($data, 'address'))
			? $this->normalizeAddress($data)
			: $data['address'];

		$shippingInfo = [
			'address' => $address,
			'addressType' => $this->getOrNull($data, 'addressType'),
		];

		$feeAmount = $this->getOrNull($data, 'feeAmount');

		if ($feeAmount) {
			$shippingInfo['feeAmount'] = $feeAmount;
		}

		$feeCurrency = $this->getOr($data, 'feeCurrency', $this->currency);

		if ($feeCurrency) {
			$shippingInfo['feeCurrency'] = $feeCurrency;
		}

		return $shippingInfo;
	}

	private function normalizeAddress($data)
	{
		return [
			'line1' => $this->getOrNull($data, 'line1'),
			'line2' => $this->getOrNull($data, 'line2'),
			'line3' => $this->getOrNull($data, 'line3'),
			'line4' => $this->getOrNull($data, 'line4'),
			'line5' => $this->getOrNull($data, 'line5'),
			'subLocality' => $this->getOrNull($data, 'subLocality'),
			'locality' => $this->getOrNull($data, 'locality'),
			'administrativeArea' => $this->getOrNull($data, 'administrativeArea'),
			'postalCode' => $this->getOrNull($data, 'postalCode'),
			'country' => $this->getOrNull($data, 'country')
		];
	}

	private function normalizeItemData($data)
	{
		if (is_null($data)) {
			return null;
		}

		$result = [];
		for ($i = 0; $i < count($data); ++$i) {
			array_push($result, [
				'price' => $this->getOrNull($data[$i], 'price'),
				'priceData' => $this->normalizePriceData($data[$i]),
				'quantity' => $this->getOrNull($data[$i], 'quantity'),
				'description' => $this->getOrNull($data[$i], 'description'),
				'metadata' => $this->getOrNull($data[$i], 'metadata')
			]);
		}

		return $result;
	}

	private function normalizePriceData($data)
	{
		if (is_null($this->getOrNull($data, 'productData'))) {
			$productData = [
				'name' => $this->getOrNull($data, 'name'),
				'brand' => $this->getOrNull($data, 'brand'),
				'categories' => $this->getOrNull($data, 'categories'),
				'gtin' => $this->getOrNull($data, 'gtin'),
				'images' => $this->getOrNull($data, 'images'),
				'reference' => $this->getOrNull($data, 'reference'),
				'url' => $this->getOrNull($data, 'url'),
				'description' => $this->getOrNull($data, 'description'),
				'metadata' => $this->getOrNull($data, 'metadata')
			];
		} else {
			$productData =  $data['productData'];
		}

		return [
			'productData' => $productData,
			'amount' => $this->getOrNull($data, 'amount'),
			'currency' => $this->getOrNull($data, 'currency'),
			'metadata' => $this->getOrNull($data, 'metadata')
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


	private function getOr($array, $key, $default)
	{
		$value = $default;
		if (array_key_exists($key, $array)) {
			$value = $array[$key];
		}
		return $value;
	}
}
