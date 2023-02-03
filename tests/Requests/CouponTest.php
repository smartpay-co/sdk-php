<?php

namespace Tests\Requests;

use Smartpay\Errors\InvalidRequestPayloadError;
use Tests\TestCase;
use Smartpay\Requests\Coupon;

final class CouponTest extends TestCase
{
    /**
     * @throws InvalidRequestPayloadError
     */
    public function testToRequest()
    {
        $payload = [
            'name' => 'test coupon',
            'discountType' => 'percentage',
            'discountPercentage' => 50
        ];

        $request = new Coupon($payload);
        $r = $request->toRequest();

        $this->assertEquals([
            'currency' => null,
            'discountAmount' => null,
            'discountPercentage' => 50,
            'discountType' => 'percentage',
            'expiresAt' => null,
            'maxRedemptionCount' => null,
            'metadata' => null,
            'name' => 'test coupon'
        ], $r);
    }

    public function testToRequestThrowsExceptionIfNameIsMissing()
    {
        $payload = [];

        $request = new Coupon($payload);
        $this->expectException(InvalidRequestPayloadError::class);
        $request->toRequest();
    }

    public function testToRequestThrowsExceptionIfDiscountTypeIsNotAllowed()
    {
        $payload = [
            'name' => 'test coupon',
            'discountType' => 'not allowed',
            'discountPercentage' => 50
        ];

        $request = new Coupon($payload);
        $this->expectException(InvalidRequestPayloadError::class);
        $request->toRequest();
    }

    public function testToRequestThrowsExceptionIfDiscountAmountIsMissingWhenDiscountTypeIsAmount()
    {
        $payload = [
            'name' => 'test coupon',
            'discountType' => 'amount',
            'discountPercentage' => 50
        ];

        $request = new Coupon($payload);
        $this->expectException(InvalidRequestPayloadError::class);
        $request->toRequest();
    }

    public function testToRequestThrowsExceptionIfCurrencyIsMissingWhenDiscountTypeIsAmount()
    {
        $payload = [
            'name' => 'test coupon',
            'discountType' => 'amount',
            'discountAmount' => 50
        ];

        $request = new Coupon($payload);
        $this->expectException(InvalidRequestPayloadError::class);
        $request->toRequest();
    }

    public function testToRequestThrowsExceptionIfDiscountPercentageIsMissingWhenDiscountTypeIsPercentage()
    {
        $payload = [
            'name' => 'test coupon',
            'discountType' => 'percentage',
            'discountAmount' => 50
        ];

        $request = new Coupon($payload);
        $this->expectException(InvalidRequestPayloadError::class);
        $request->toRequest();
    }
}
