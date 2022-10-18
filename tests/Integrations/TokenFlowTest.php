<?php

namespace Tests\Integrations;

use Smartpay\Errors\InvalidRequestPayloadError;
use Tests\TestCase;

final class TokenFlowTest extends TestCase
{
    /**
     * @throws InvalidRequestPayloadError
     */
    public function testTokenFlow()
    {
        $api = new \Smartpay\Api(getenv('SMARTPAY_SECRET_KEY'), getenv('SMARTPAY_PUBLIC_KEY'));

        // Create a checkoutSession for a token
        $checkoutSessionResponse = $api->checkoutSession([
            "mode" => "token",
            "tokenType" => "recurring",
            "customerInfo" => [
                "accountAge" => 20,
                "email" => "merchant-support@smartpay.co",
                "firstName" => "田中",
                "lastName" => "太郎",
                "firstNameKana" => "たなか",
                "lastNameKana" => "たろう",
                "address" => [
                    "line1" => "3-6-7",
                    "line2" => "青山パラシオタワー 11階",
                    "subLocality" => "",
                    "locality" => "港区北青山",
                    "administrativeArea" => "東京都",
                    "postalCode" => "107-0061",
                    "country" => "JP"
                ],
                "dateOfBirth" => "1985-06-30",
                "gender" => "male"
            ],
            "reference" => "order_ref_1234567",
            "successUrl" => "https://docs.smartpay.co/example-pages/checkout-successful",
            "cancelUrl" => "https://docs.smartpay.co/example-pages/checkout-canceled",
        ]);

        $checkoutSession = $checkoutSessionResponse->asJson();

        static::assertArrayHasKey('id', $checkoutSession);
        static::assertArrayHasKey('token', $checkoutSession);
        static::assertArrayHasKey('id', $checkoutSession['token']);
        static::assertSame('recurring', $checkoutSession['token']['type']);
    }
}
