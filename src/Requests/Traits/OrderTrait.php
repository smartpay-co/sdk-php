<?php

namespace Smartpay\Requests\Traits;

trait OrderTrait
{
    protected $currency;

    protected function getCurrency()
    {
        if (!$this->currency) {
            $this->currency = $this->getOrNull($this->rawPayload, 'currency');
        }
        return $this->currency;
    }

    protected function normalizeCustomerInfo()
    {
        $data = [];

        if (array_key_exists('customerInfo', $this->rawPayload)) {
            $data = $this->rawPayload['customerInfo'];
        } elseif (array_key_exists('customer', $this->rawPayload)) {
            trigger_error("Since smartpay-co/sdk-php v0.6.0: `customer` field in request payload was deprecated. Use `customerInfo` instead.", E_USER_DEPRECATED);
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

    protected function normalizeShippingInfo($data)
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

        if (isset($feeAmount)) {
            $shippingInfo['feeAmount'] = $feeAmount;
        }

        $feeCurrency = $this->getOr($data, 'feeCurrency', $this->getCurrency());

        if ($feeCurrency) {
            $shippingInfo['feeCurrency'] = $feeCurrency;
        }

        return $shippingInfo;
    }

    protected function normalizeAddress($data)
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

    protected function normalizeItemData($data)
    {
        if (is_null($data)) {
            return null;
        }

        $result = [];
        for ($i = 0; $i < count($data); ++$i) {
            $result[] = [
                'quantity' => $this->getOrNull($data[$i], 'quantity'),
                'label' => $this->getOrNull($data[$i], 'label'),
                'brand' => $this->getOrNull($data[$i], 'brand'),
                'name' => $this->getOrNull($data[$i], 'name'),
                'amount' => $this->getOrNull($data[$i], 'amount'),
                'currency' => $this->getOrNull($data[$i], 'currency'),
                'categories' => $this->getOrNull($data[$i], 'categories'),
                'gtin' => $this->getOrNull($data[$i], 'gtin'),
                'images' => $this->getOrNull($data[$i], 'images'),
                'reference' => $this->getOrNull($data[$i], 'reference'),
                'url' => $this->getOrNull($data[$i], 'url'),
                'description' => $this->getOrNull($data[$i], 'description'),
                'priceDescription' => $this->getOrNull($data[$i], 'priceDescription'),
                'productDescription' => $this->getOrNull($data[$i], 'productDescription'),
                'metadata' => $this->getOrNull($data[$i], 'metadata'),
                'priceMetadata' => $this->getOrNull($data[$i], 'priceMetadata'),
                'productMetadata' => $this->getOrNull($data[$i], 'productMetadata'),
                'kind' => $this->getOrNull($data[$i], 'kind')
            ];
        }

        return $result;
    }

    protected function getShippingInfo()
    {
        $data = null;
        if (array_key_exists('shippingInfo', $this->rawPayload)) {
            $data = $this->rawPayload['shippingInfo'];
        } elseif (array_key_exists('shipping', $this->rawPayload)) {
            trigger_error("Since smartpay-co/sdk-php v0.6.0: `shipping` field in request payload was deprecated. Use `shippingInfo` instead.", E_USER_DEPRECATED);
            $data = $this->rawPayload['shipping'];
        }
        return $this->normalizeShippingInfo($data);
    }
}
