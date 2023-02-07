<?php

namespace Tests\Integrations;

use GuzzleHttp\Exception\GuzzleException;

/**
 * @group integration
 */
final class CouponTest extends BaseTestCase
{
    /**
     * @throws GuzzleException
     */
    public function testCreateCoupon()
    {
        $coupon = $this->getApiClient()->createCoupon([
            'name' => 'test coupon from sdk-php',
            'discountType' => 'amount',
            'discountAmount' => 50,
            'currency' => 'JPY'
        ])->asJson();
        $this->assertArrayHasKey('id', $coupon);

        return $coupon['id'];
    }

    /**
     * @depends testCreateCoupon
     * @throws GuzzleException
     */
    public function testUpdateCoupon($couponId)
    {
        $updateCoupon = $this->getApiClient()->updateCoupon([
            'id' => $couponId,
            'name' => 'updated coupon from sdk-php',
            'active' => false
        ])->asJson();
        $this->assertSame('updated coupon from sdk-php', $updateCoupon['name']);
        return $updateCoupon['id'];
    }

    /**
     * @depends testUpdateCoupon
     * @throws GuzzleException
     */
    public function testGetCoupon($couponId)
    {
        $getCoupon = $this->getApiClient()->getCoupon(['id' => $couponId]);
        $this->assertSame(200, $getCoupon->getStatusCode());
        $this->assertSame(false, $getCoupon->asJson()['active']);
    }

    /**
     * @depends testCreateCoupon
     * @throws GuzzleException
     */
    public function testGetCoupons()
    {
        $couponsResponse = $this->getApiClient()->getCoupons()->asJson();
        $this->assertSame('coupon', $couponsResponse['data'][0]['object']);
    }
}
