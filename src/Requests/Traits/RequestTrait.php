<?php

namespace Smartpay\Requests\Traits;

trait RequestTrait
{
    private $rawPayload;

    public function __construct($rawPayload)
    {
        $this->rawPayload = $rawPayload;
    }

    protected function getOrNull($array, $key)
    {
        return $this->getOr($array, $key, null);
    }


    protected function getOr($array, $key, $default)
    {
        $value = $default;
        if (array_key_exists($key, $array)) {
            $value = $array[$key];
        }
        return $value;
    }

    protected function requiredKeysExist($payload, $required_keys, $fallback_keys = [])
    {
        for ($i = 0; $i < count($required_keys); ++$i) {
            if (!array_key_exists($required_keys[$i], $payload)) {
                if (!in_array($required_keys[$i], $fallback_keys)) {
                    return false;
                }
            }
        }
        return true;
    }
}