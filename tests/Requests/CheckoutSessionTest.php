<?php

namespace Tests\Requests;

use Tests\TestCase;
use Smartpay\Requests\CheckoutSession;

final class CheckoutSessionTest extends TestCase
{
    public function testToRequest()
    {
        $payload = [
            "amount" => 350,
            "currency" => "JPY",
            "items" => [[
                "name" => "オリジナルス STAN SMITH",
                "amount" => 250,
                "currency" => "JPY",
                "quantity" => 1
            ]],
            "customer" => [
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
            "shipping" => [
                "line1" => "line1",
                "locality" => "locality",
                "postalCode" => "123",
                "country" => "JP",
            ],
            "reference" => "order_ref_1234567",
            "successUrl" => "https://docs.smartpay.co/example-pages/checkout-successful",
            "cancelUrl" => "https://docs.smartpay.co/example-pages/checkout-canceled",
        ];

        $request = new CheckoutSession($payload);
        $this->assertEquals($request->toRequest(), [
            "customerInfo" => [
                "accountAge" => 20,
                "address" => [
                    "administrativeArea" => "東京都",
                    "country" => "JP",
                    "line1" => "3-6-7",
                    "line2" => "青山パラシオタワー 11階",
                    "locality" => "港区北青山",
                    "postalCode" => "107-0061",
                    "subLocality" => ""
                ],
                "dateOfBirth" => "1985-06-30",
                "emailAddress" => "merchant-support@smartpay.co",
                "firstName" => "田中",
                "firstNameKana" => "たなか",
                "lastName" => "太郎",
                "lastNameKana" => "たろう",
                "legalGender" => "male",
                "phoneNumber" => null,
                "reference" => null
            ],
            "reference" => "order_ref_1234567",
            "metadata" => null,
            "amount" => 350,
            "captureMethod" => null,
            "currency" => "JPY",
            "items" => [[
                "description" => null,
                "kind" => null,
                "priceDescription" => null,
                "productDescription" => null,
                "metadata" => null,
                "priceMetadata" => null,
                "productMetadata" => null,
                "amount" => 250,
                "currency" => "JPY",
                "brand" => null,
                "label" => null,
                "categories" => null,
                "gtin" => null,
                "images" => null,
                "name" => "オリジナルス STAN SMITH",
                "reference" => null,
                "url" => null,
                "quantity" => 1
            ]],
            "shippingInfo" => [
                "address" => [
                    "administrativeArea" => null,
                    "country" => "JP",
                    "line1" => "line1",
                    "line2" => null,
                    "line3" => null,
                    "line4" => null,
                    "line5" => null,
                    "locality" => "locality",
                    "postalCode" => "123",
                    "subLocality" => null
                ],
                "addressType" => null,
                "feeCurrency" => "JPY"
            ],
            "successUrl" => "https://docs.smartpay.co/example-pages/checkout-successful",
            "cancelUrl" => "https://docs.smartpay.co/example-pages/checkout-canceled",
        ]);
    }
}
