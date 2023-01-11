<?php

namespace Tests\Integrations;

use Smartpay\Errors\InvalidRequestPayloadError;

/**
 * @group integration
 */
final class TokenFlowTest extends BaseTestCase
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

        $this->assertArrayHasKey('id', $checkoutSession);
        $this->assertArrayHasKey('token', $checkoutSession);
        $this->assertArrayHasKey('id', $checkoutSession['token']);

        // Approve token
        $tokenId = $checkoutSession['token']['id'];
        $accessToken = $this->userLoginAndGetAccessToken();
        $this->getHttpClient()->put('/tokens/' . $tokenId . '/approve', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
                'ContentType' => 'application/json'
            ]
        ]);

        // Test disable token
        $api->disableToken(['id' => $tokenId]);
        $token = $api->getToken(['id' => $tokenId])->asJson();
        $this->assertSame("disabled", $token['status']);

        // Test enable token
        $api->enableToken(['id' => $tokenId]);
        $token = $api->getToken(['id' => $tokenId])->asJson();
        $this->assertSame("active", $token['status']);

        // Test list token
        $tokens = $api->getTokens(['maxResults' => 2])->asJson();
        $this->assertGreaterThan(0, $tokens['data']);

        // Create an order using this token
        $payload = [
            "token" => $tokenId,
            "amount" => 350,
            "currency" => "JPY",
            "items" => [
                [
                    "currency" => "JPY",
                    "amount" => 100,
                    "name" => "explicit taxes",
                    "kind" => "tax"
                ],
                [
                    "name" => "オリジナルス STAN SMITH",
                    "amount" => 250,
                    "currency" => "JPY",
                    "quantity" => 1
                ]
            ],
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
            "shippingInfo" => [
                "line1" => "line1",
                "locality" => "locality",
                "postalCode" => "123",
                "country" => "JP",
            ],
            "reference" => "order_ref_1234567",
        ];
        $order = $api->createOrder($payload)->asJson();
        $this->assertArrayHasKey('id', $order);

        $token = $api->getToken(['id' => $tokenId])->asJson();
        $this->assertSame("active", $token['status']);

        // Test delete token
        $response = $api->deleteToken(['id' => $tokenId]);
        $this->assertSame(204, $response->getStatusCode());
    }
}
