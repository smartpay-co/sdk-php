<?php

namespace Tests\Requests;

use Tests\TestCase;
use Smartpay\Requests\CheckoutSessionForToken;
use Smartpay\Errors\InvalidRequestPayloadError;

final class CheckoutSessionForTokenTest extends TestCase
{
    /**
     * @throws InvalidRequestPayloadError
     */
    public function testToRequest()
    {
        $payload = [
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
        ];

        $request = new CheckoutSessionForToken($payload);

        $this->assertEquals([
            "mode" => "token",
            "tokenType" => "recurring",
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
            "locale" => null,
            "reference" => "order_ref_1234567",
            "metadata" => null,
            "successUrl" => "https://docs.smartpay.co/example-pages/checkout-successful",
            "cancelUrl" => "https://docs.smartpay.co/example-pages/checkout-canceled",
        ], $request->toRequest());
    }

    /**
     * @throws InvalidRequestPayloadError
     */
    public function testToRequestThrowsExceptionIfModeIsNotToken()
    {
        $payload = [
            "mode" => "something else",
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
        ];

        $request = new CheckoutSessionForToken($payload);
        $this->expectException(InvalidRequestPayloadError::class);
        $request->toRequest();
    }

    /**
     * @throws InvalidRequestPayloadError
     */
    public function testToRequestThrowsExceptionIfTokenTypeIsNotAllowed()
    {
        $payload = [
            "mode" => "token",
            "tokenType" => "not_allowed",
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
        ];

        $request = new CheckoutSessionForToken($payload);
        $this->expectException(InvalidRequestPayloadError::class);
        $request->toRequest();
    }

    /**
     * @throws InvalidRequestPayloadError
     */
    public function testToRequestThrowsExceptionIfLocaleIsNotAllowed()
    {
        $payload = [
            "mode" => "token",
            "tokenType" => "recurring",
            "locale" => "not_allowed",
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
        ];

        $request = new CheckoutSessionForToken($payload);
        $this->expectException(InvalidRequestPayloadError::class);
        $request->toRequest();
    }
}
