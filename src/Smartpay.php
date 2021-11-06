<?php

namespace Smartpay;

/**
 * Class Smartpay.
 */
class Smartpay
{
    const VERSION = '0.0.1';

    const DEFAULT_API_URL = "https://api.smartpay.co/v1";
    const DEFAULT_CHECKOUT_URL = "https://checkout.smartpay.co";
    const DEFAULT_POST_TIMEOUT = 30;

    public static $apiUrl = self::DEFAULT_API_URL;
    public static $checkoutUrl = self::DEFAULT_CHECKOUT_URL;
    public static $postTimeout = self::DEFAULT_POST_TIMEOUT;

    public static $publicKey;
    public static $secretKey;

    public static function setApiUrl($apiUrl)
    {
	self::$apiUrl = $apiUrl;
    }

    public static function setCheckoutUrl($checkoutUrl)
    {
	self::$checkoutUrl = $checkoutUrl;
    }

    public static function setPostTimeout($postTimeout)
    {
	self::$postTimeout = $postTimeout;
    }

    public static function setPublicKey($publicKey)
    {
	self::$publicKey = $publicKey;
    }

    public static function setSecretKey($secretKey)
    {
	self::$secretKey = $secretKey;
    }

    public static function getApiUrl()
    {
	if(is_null(self::$apiUrl)) {
	    self::setApiUrl(self::DEFAULT_API_URL);
	}
	return self::$apiUrl;
    }

    public static function getCheckoutUrl()
    {
	if(is_null(self::$checkoutUrl)) {
	    self::setCheckoutUrl(self::DEFAULT_CHECKOUT_URL);
	}
	return self::$checkoutUrl;
    }

    public static function getPostTimeout()
    {
	if(intval(self::$postTimeout) === 0) {
	    self::setPostTimeout(self::DEFAULT_POST_TIMEOUT);
	}
	return self::$postTimeout;
    }

    public static function getPublicKey()
    {
	return self::$publicKey;
    }

    public static function getSecretKey()
    {
	return self::$secretKey;
    }
}
