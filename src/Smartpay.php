<?php

namespace Smartpay;

/**
 * Class Smartpay.
 */
class Smartpay
{
    const DEV_LANG = 'php';
    const VERSION = '0.6.0';

    const DEFAULT_API_URL = "https://api.smartpay.co/v1";

    const REJECT_REQUEST_BY_CUSTOMER = 'requested_by_customer';
    const REJECT_FRAUDULENT = 'fraudulent';

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
