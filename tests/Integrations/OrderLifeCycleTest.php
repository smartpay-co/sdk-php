<?php

namespace Tests\Integrations;

use Smartpay\Smartpay;

/**
 * @group integration
 */
final class OrderLifeCycleTest extends BaseTestCase
{
    public function testOrderLifecycle()
    {
        $api = new \Smartpay\Api(getenv('SMARTPAY_SECRET_KEY'), getenv('SMARTPAY_PUBLIC_KEY'));

        $checkoutSessionResponse = $api->checkoutSession([
            'amount' => 400,
            'currency' => 'JPY',
            'items' => [[
                'name' => 'オリジナルス STAN SMITH',
                'amount' => 250,
                'currency' => 'JPY',
                'quantity' => 1,
            ]],
            'customerInfo' => [
                'accountAge' => 20,
                'email' => 'merchant-support@smartpay.co',
                'firstName' => '田中',
                'lastName' => '太郎',
                'firstNameKana' => 'たなか',
                'lastNameKana' => 'たろう',
                'address' => [
                    'line1' => '北青山 3-6-7',
                    'line2' => '青山パラシオタワー 11階',
                    'subLocality' => '',
                    'locality' => '港区',
                    'administrativeArea' => '東京都',
                    'postalCode' => '107-0061',
                    'country' => 'JP',
                ],
                'dateOfBirth' => '1985-06-30',
                'gender' => 'male',
            ],
            'shippingInfo' => [
                'address' => [
                    'line1' => '北青山 3-6-7',
                    'line2' => '青山パラシオタワー 11階',
                    'subLocality' => '',
                    'locality' => '港区',
                    'administrativeArea' => '東京都',
                    'postalCode' => '107-0061',
                    'country' => 'JP',
                ],
                'feeAmount' => 150,
                'feeCurrency' => 'JPY',
            ],

            'captureMethod' => 'manual',

            'reference' => 'order_ref_1234567',
            'successUrl' => 'https://docs.smartpay.co/example-pages/checkout-successful',
            'cancelUrl' => 'https://docs.smartpay.co/example-pages/checkout-canceled'
        ]);

        $checkoutSession = $checkoutSessionResponse->asJson();

        static::assertArrayHasKey('id', $checkoutSession);

        $orderId = $checkoutSession['order']['id'];

        $accessToken = $this->userLoginAndGetAccessToken();

        $authorizationPayload = [
            "paymentMethod" => "pm_test_visaApproved",
            "paymentPlan" => "pay_in_three"
        ];
        $this->getHttpClient()->post('/orders/' . $orderId . '/authorizations', [
            'headers' => [
                'Authorization' => 'Bearer ' . strval($accessToken),
                'Accept' => 'application/json',
                'ContentType' => 'application/json'
            ],
            'json' => $authorizationPayload
        ]);

        $PAYMENT_AMOUNT = 50;

        $payment1Response = $api->createPayment([
            'order' => $orderId,
            'amount' => $PAYMENT_AMOUNT,
            'currency' => 'JPY',
            'reference' => '12345',
            'cancelRemainder' => 'manual',
        ]);

        $payment2Response = $api->capture([
            'order' => $orderId,
            'amount' => $PAYMENT_AMOUNT + 1,
            'currency' => 'JPY',
            'reference' => '12345',
            'cancelRemainder' => 'manual',
        ]);

        $payment1 = $payment1Response->asJson();
        $payment2 = $payment2Response->asJson();

        static::assertArrayHasKey('id', $payment1);
        static::assertArrayHasKey('id', $payment2);
        static::assertEquals($payment2['amount'], $PAYMENT_AMOUNT + 1);

        $retrivedPayment2Response = $api->getPAyment(['id' => $payment2['id']]);
        $retrivedPayment2 = $retrivedPayment2Response->asJson();

        static::assertSame($retrivedPayment2['id'], $payment2['id']);
        static::assertEquals($retrivedPayment2['amount'], $PAYMENT_AMOUNT + 1);

        $orderResponse = $api->getOrder([
            'id' => $orderId,
        ]);
        $order = $orderResponse->asJson();
        $refundablePayment = $order['payments'][0];

        $REFUND_AMOUNT = 1;

        $refund1Response = $api->createRefund([
            'payment' => $refundablePayment,
            'amount' => $REFUND_AMOUNT,
            'currency' => 'JPY',
            'reference' => '12345',
            'reason' => Smartpay::REJECT_REQUEST_BY_CUSTOMER,

        ]);

        $refund2Response = $api->refund([
            'payment' => $refundablePayment,
            'amount' => $REFUND_AMOUNT + 1,
            'currency' => 'JPY',
            'reference' => '12345',
            'reason' => Smartpay::REJECT_REQUEST_BY_CUSTOMER,
        ]);

        $refund1 = $refund1Response->asJson();
        $refund2 = $refund2Response->asJson();

        static::assertArrayHasKey('id', $refund1);
        static::assertArrayHasKey('id', $refund2);
        static::assertSame($refund2['amount'], $REFUND_AMOUNT + 1);



        $cancelOrder = $api->cancelOrder(['id' => $orderId])->asJson();
        static::assertSame($cancelOrder['status'], 'succeeded');
    }

    public function testLineItems()
    {
        $api = new \Smartpay\Api(getenv('SMARTPAY_SECRET_KEY'), getenv('SMARTPAY_PUBLIC_KEY'));

        $checkoutSessionResponse = $api->checkoutSession([
            "amount" => 601,
            "currency" => "JPY",
            "successUrl" => "https://docs.smartpay.co/en/example-pages/checkout-successful/",
            "cancelUrl" => "https://docs.smartpay.co/en/example-pages/checkout-canceled/",
            "captureMethod" => "automatic",
            "items" => [
                [
                    "currency" => "JPY",
                    "amount" => 500,
                    "name" => "Merchant special discount",
                    "kind" => "discount"
                ],
                [
                    "currency" => "JPY",
                    "amount" => 100,
                    "name" => "explicit taxes",
                    "kind" => "tax"
                ],
                [
                    "currency" => "JPY",
                    "amount" => 1000,
                    "name" => "ice cream",
                    "quantity" => 1
                ]
            ],
            "customerInfo" => [
                "emailAddress" => "john@smartpay.co",
                "firstName" => "John",
                "lastName" => "Doe",
                "firstNameKana" => "ジョン",
                "lastNameKana" => "ドエ",
                "phoneNumber" => "+818000000000",
                "dateOfBirth" => "2000-01-01",
                "legalGender" => "male",
                "address" => [
                    "line1" => "",
                    "line2" => "800",
                    "locality" => "世田谷区",
                    "administrativeArea" => "東京都",
                    "postalCode" => "155-0031",
                    "country" => "jp"
                ],
                "accountAge" => 30
            ],
            "shippingInfo" => [
                "address" => [
                    "line1" => "1-2-3",
                    "line2" => "12",
                    "locality" => "locality",
                    "postalCode" => "12345678",
                    "country" => "jp"
                ],
                "feeAmount" => 1,
                "feeCurrency" => "jpy"
            ]
        ]);

        $checkoutSession = $checkoutSessionResponse->asJson();

        static::assertArrayHasKey('id', $checkoutSession);

        $orderId = $checkoutSession['order']['id'];
        $orderResponse = $api->getOrder([
            'id' => $orderId,
            'expand' => 'all'
        ]);
        $order = $orderResponse->asJson();
        static::assertArrayHasKey('kind', $order['lineItems'][0]);
        static::assertArrayHasKey('kind', $order['lineItems'][1]);
        static::assertArrayHasKey('kind', $order['lineItems'][2]);
    }
}
