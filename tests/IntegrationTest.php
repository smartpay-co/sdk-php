<?php

namespace Tests\Smartpay;

use Tests\TestCase;

use Smartpay\Smartpay;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;

final class OrderLifecycleTest extends TestCase
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

        $client = new GuzzleClient([
            'base_uri' => "https://" . getenv('API_BASE'),
            'timeout'  => Smartpay::getPostTimeout(),
        ]);

        $loginPayload = [
            "emailAddress" => getenv('TEST_USERNAME'),
            "password" => getenv('TEST_PASSWORD')
        ];
        $loginResponse = $client->post('/consumers/auth/login', [
            'headers' => [
                'Accept' => 'application/json',
                'ContentType' => 'application/json'
            ],
            'json' => $loginPayload
        ]);
        $loginResponseData = json_decode(strval($loginResponse->getBody()), true);
        $accessToken = $loginResponseData['accessToken'];

        $authorizationPayload = [
            "paymentMethod" => "pm_test_visaApproved",
            "paymentPlan" => "pay_in_three"
        ];
        $authorizationResponse = $client->post('/orders/' . $orderId . '/authorizations', [
            'headers' => [
                'Authorization' => 'Bearer ' . strval($accessToken),
                'Accept' => 'application/json',
                'ContentType' => 'application/json'
            ],
            'json' => $authorizationPayload
        ]);
        // $authorizationResponseData = json_decode(strval($authorizationResponse->getBody()), true);

        $PAYMENT_AMOUNT = 50;

        $payment1Response = $api->createPayment([
            'order' => $orderId,
            'amount' => $PAYMENT_AMOUNT,
            'currency' => 'JPY',
            'reference' => '12345',
        ]);

        $payment2Response = $api->capture([
            'order' => $orderId,
            'amount' => $PAYMENT_AMOUNT,
            'currency' => 'JPY',
            'reference' => '12345',
        ]);

        $payment1 = $payment1Response->asJson();
        $payment2 = $payment2Response->asJson();

        static::assertArrayHasKey('id', $payment1);
        static::assertArrayHasKey('id', $payment2);
        static::assertSame($payment2['amount'], $PAYMENT_AMOUNT);

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
            'amount' => $REFUND_AMOUNT,
            'currency' => 'JPY',
            'reference' => '12345',
            'reason' => Smartpay::REJECT_REQUEST_BY_CUSTOMER,
        ]);

        $refund1 = $refund1Response->asJson();
        $refund2 = $refund2Response->asJson();

        static::assertArrayHasKey('id', $refund1);
        static::assertArrayHasKey('id', $refund2);
        static::assertSame($refund2['amount'], $REFUND_AMOUNT);
    }
}
