<?php

namespace Tests\Integrations;

use GuzzleHttp\Exception\GuzzleException;
use Smartpay\Errors\InvalidRequestPayloadError;
use Smartpay\Smartpay;

/**
 * @group integration
 */
final class OrderLifeCycleTest extends BaseTestCase
{
    /**
     * @throws InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function testOrderLifecycle()
    {
        $api = $this->getApiClient();

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

        $this->assertArrayHasKey('id', $checkoutSession);
        $orderId = $checkoutSession['order']['id'];

        // Test getCheckoutSession
        $checkoutSessionId = $checkoutSession['id'];
        $getCheckoutSessionResponse = $api->getCheckoutSession(['id' => $checkoutSessionId]);
        $this->assertEquals($checkoutSessionId, $getCheckoutSessionResponse->asJson()['id']);

        // Test getCheckoutSessions
        $getCheckoutSessionsResponse = $api->getCheckoutSessions(['maxResults' => 3]);
        $this->assertEquals(3, $getCheckoutSessionsResponse->asJson()['maxResults']);

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

        $this->assertArrayHasKey('id', $payment1);
        $this->assertArrayHasKey('id', $payment2);
        $this->assertEquals($PAYMENT_AMOUNT + 1, $payment2['amount']);

        // test updatePayment
        $updatePaymentResponse = $api->updatePayment(['id' => $payment2['id'], 'reference' => '54321']);
        $getPayment2Response = $api->getPayment(['id' => $payment2['id']]);
        $this->assertEquals('54321', $updatePaymentResponse->asJson()['reference']);

        $getPayment2 = $getPayment2Response->asJson();

        $this->assertSame($getPayment2['id'], $payment2['id']);
        $this->assertEquals($PAYMENT_AMOUNT + 1, $getPayment2['amount']);
        $this->assertEquals('54321', $getPayment2['reference']);

        // test getPayments
        $getPaymentsResponse = $api->getPayments(['maxResults' => 3]);
        $this->assertEquals(3, $getPaymentsResponse->asJson()['maxResults']);
        $this->assertEquals('payment', $getPaymentsResponse->asJson()['data'][0]['object']);

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

        $this->assertArrayHasKey('id', $refund1);
        $this->assertArrayHasKey('id', $refund2);
        $this->assertSame($refund2['amount'], $REFUND_AMOUNT + 1);

        // test updateRefund
        $updateRefundResponse = $api->updateRefund(['id' => $refund1['id'], 'reference' => '54321']);
        $this->assertEquals('54321', $updateRefundResponse->asJson()['reference']);
        $getRefundResponse = $api->getRefund(['id' => $refund1['id']]);
        $this->assertEquals('54321', $getRefundResponse->asJson()['reference']);

        // test getRefunds
        $getRefundsResponse = $api->getRefunds(['maxResults' => 3]);
        $this->assertEquals(3, $getRefundsResponse->asJson()['maxResults']);
        $this->assertEquals('refund', $getRefundsResponse->asJson()['data'][0]['object']);

        $cancelOrder = $api->cancelOrder(['id' => $orderId])->asJson();
        $this->assertSame($cancelOrder['status'], 'succeeded');
    }

    /**
     * @throws InvalidRequestPayloadError
     * @throws GuzzleException
     */
    public function testLineItems()
    {
        $api = $this->getApiClient();

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

        $this->assertArrayHasKey('id', $checkoutSession);

        $orderId = $checkoutSession['order']['id'];
        $orderResponse = $api->getOrder([
            'id' => $orderId,
            'expand' => 'all'
        ]);
        $order = $orderResponse->asJson();
        $this->assertArrayHasKey('kind', $order['lineItems'][0]);
        $this->assertArrayHasKey('kind', $order['lineItems'][1]);
        $this->assertArrayHasKey('kind', $order['lineItems'][2]);
    }
}
