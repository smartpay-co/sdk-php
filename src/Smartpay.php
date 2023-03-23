<?php

namespace Smartpay;

/**
 * Class Smartpay.
 */
class Smartpay
{
    const DEV_LANG = 'php';
    const VERSION = '0.7.0';

    const DEFAULT_API_URL = "https://api.smartpay.co/v1";

    const ADDRESS_TYPE_HOME = 'home';
    const ADDRESS_TYPE_GIFT = 'gift';
    const ADDRESS_TYPE_LOCKER = 'locker';
    const ADDRESS_TYPE_OFFICE = 'office';
    const ADDRESS_TYPE_STORE = 'store';

    const CAPTURE_METHOD_AUTOMATIC = 'autommatic';
    const CAPTURE_METHOD_MANUAL = 'manual';

    const COUPON_DISCOUNT_TYPE_AMOUNT = 'amount';
    const COUPON_DISCOUNT_TYPE_PERCENTAGE = 'percentage';

    const ORDER_STATUS_SUCCEEDED = 'succeeded';
    const ORDER_STATUS_CANCELED = 'canceled';
    const ORDER_STATUS_REJECTED = 'rejected';
    const ORDER_STATUS_FAILED = 'failed';
    const ORDER_STATUS_REQUIRES_AUTHORIZATION = 'requires_authorization';

    const CANCEL_REMAINDER_AUTOMATIC = 'autommatic';
    const CANCEL_REMAINDER_MANUAL = 'manual';

    const REJECT_REQUEST_BY_CUSTOMER = 'requested_by_customer';
    const REJECT_FRAUDULENT = 'fraudulent';

    const TOKEN_STATUS_ACTIVE = 'active';
    const TOKEN_STATUS_DISABLED = 'disabled';
    const TOKEN_STATUS_REJECTED = 'rejected';
    const TOKEN_STATUS_REQUIRES_AUTHORIZATION = 'requires_authorization';

    private $apiUrl;

    private $publicKey;
    private $secretKey;

    public function __construct($secretKey = null, $publicKey = null, $apiUrl = null)
    {
        $this->publicKey = $publicKey ?: getenv('SMARTPAY_PUBLIC_KEY') ?: '';
        $this->secretKey = $secretKey ?: getenv('SMARTPAY_SECRET_KEY') ?: '';
        $this->apiUrl = $apiUrl ?: getenv('SMARTPAY_API_PREFIX') ?: self::DEFAULT_API_URL;
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }
}
